import { GeolocationCoordinates } from "../../../../js/Base/GeolocationCoordinates.js";
import { GeolocationMapBase } from "../../../../js/MapProvider/GeolocationMapBase.js";
import { GeolocationBoundaries } from "../../../../js/Base/GeolocationBoundaries.js";
import { BingMapMarker } from "../BingMapMarker.js";

/* global Microsoft */

/**
 * @typedef BingMapSettings
 *
 * @extends GeolocationMapSettings
 *
 * @prop {String} bing_api_url
 * @prop {String} zoom
 * @prop {String} show_breadcrumb
 * @prop {String} show_dashboard
 * @prop {String} show_locate_me_button
 * @prop {String} show_map_type_selector
 * @prop {String} show_traffic_button
 * @prop {String} show_terms_link
 * @prop {String} show_zoom_buttons
 * @prop {String} show_scalebar
 */

/**
 * @prop {Microsoft.Maps.Map} bingMap
 * @prop {BMapGL.Control[]} customControls
 * @prop {BingMapSettings} settings
 */
export default class Bing extends GeolocationMapBase {
  constructor(mapSettings) {
    super(mapSettings);

    this.settings.zoom = this.settings.zoom ?? 2;

    this.customControls = [];

    // Set the container size.
    this.container.style.height = this.settings.height;
    this.container.style.width = this.settings.width;
  }

  initialize() {
    return super
      .initialize()
      .then(() => {
        return new Promise((resolve) => {
          Drupal.geolocation.maps.addMapProviderCallback("Bing", resolve);
        });
      })
      .then(() => {
        return new Promise((resolve) => {
          this.bingMap = new Microsoft.Maps.Map(this.container, {
            zoom: this.settings.zoom,
            center: new Microsoft.Maps.Location(this.settings.lat, this.settings.lng),
            showBreadcrumb: !!this.settings.show_breadcrumb ?? false,
            showDashboard: !!this.settings.show_dashboard ?? false,
            showLocateMeButton: !!this.settings.show_locate_me_button ?? false,
            showMapTypeSelector: !!this.settings.show_map_type_selector ?? false,
            showTrafficButton: !!this.settings.show_traffic_button ?? false,
            showTermsLink: !!this.settings.show_terms_link ?? false,
            showZoomButtons: !!this.settings.show_zoom_buttons ?? false,
            showScalebar: !!this.settings.show_scalebar ?? false,
          });
          resolve();
        }).then(() => {
          return new Promise((resolve) => {
            let singleClick;

            Microsoft.Maps.Events.addHandler(this.bingMap, "click", (event) => {
              singleClick = setTimeout(() => {
                this.features.forEach((feature) => {
                  feature.onClick(new GeolocationCoordinates(event.location.latitude, event.location.longitude));
                });
              }, 500);
            });

            Microsoft.Maps.Events.addHandler(this.bingMap, "dblclick", (event) => {
              clearTimeout(singleClick);
              this.features.forEach((feature) => {
                feature.onDoubleClick(new GeolocationCoordinates(event.location.latitude, event.location.longitude));
              });
            });

            Microsoft.Maps.Events.addHandler(this.bingMap, "rightclick", (event) => {
              this.features.forEach((feature) => {
                feature.onContextClick(new GeolocationCoordinates(event.location.latitude, event.location.longitude));
              });
            });

            Microsoft.Maps.Events.addHandler(this.bingMap, "viewchangeend", () => {
              this.updatingBounds = false;

              this.features.forEach((feature) => {
                feature.onMapIdle();
              });
            });

            Microsoft.Maps.Events.addHandler(this.bingMap, "viewchangeend", () => {
              const bounds = this.getBoundaries();
              if (!bounds) {
                return;
              }

              this.features.forEach((feature) => {
                feature.onBoundsChanged(bounds);
              });
            });

            resolve(this);
          });
        });
      });
  }

  createMarker(coordinates, settings) {
    const marker = new BingMapMarker(coordinates, settings, this);
    this.bingMap.entities.push(marker.bingMarker);

    return marker;
  }

  getBoundaries() {
    super.getBoundaries();

    return this.normalizeBoundaries(this.bingMap.getBounds());
  }

  getMarkerBoundaries(markers) {
    super.getMarkerBoundaries(markers);

    markers = markers || this.dataLayers.get("default").markers;
    if (!markers) {
      return null;
    }

    /** @type {Microsoft.Maps.Pushpin[]} */
    const bingMarkers = [];

    markers.forEach((marker) => {
      bingMarkers.push(marker.bingMarker);
    });

    return this.normalizeBoundaries(Microsoft.Maps.SpatialMath.Geometry.bounds(bingMarkers));
  }

  setBoundaries(boundaries) {
    if (super.setBoundaries(boundaries) === false) {
      return false;
    }

    boundaries = this.denormalizeBoundaries(boundaries);

    this.bingMap.setView({ bounds: boundaries });

    return this;
  }

  getZoom() {
    this.bingMap.getZoom();
  }

  setZoom(zoom, defer) {
    if (!zoom) {
      zoom = this.settings.zoom;
    }
    zoom = parseInt(zoom);

    this.bingMap.setView({ zoom });
  }

  getCenter() {
    const center = this.bingMap.getCenter();

    return new GeolocationCoordinates(center.latitude, center.longitude);
  }

  setCenterByCoordinates(coordinates, accuracy) {
    super.setCenterByCoordinates(coordinates, accuracy);

    if (typeof accuracy === "undefined") {
      this.bingMap.setView({ center: new Microsoft.Maps.Location(coordinates.lat, coordinates.lng) });
      return;
    }

    const circle = this.addAccuracyIndicatorCircle(coordinates, accuracy);

    // Set the zoom level to the accuracy circle's size.
    this.setBoundaries(this.normalizeBoundaries(new Microsoft.Maps.LocationRect.fromLocations(circle.getLocations())));

    // Fade circle away.
    setInterval(() => {
      let fillOpacity = circle.getFillColor().getOpacity();
      fillOpacity -= 1;

      let strokeOpacity = circle.getStrokeColor().getOpacity();
      strokeOpacity -= 2;

      if (strokeOpacity > 0 && fillOpacity > 0) {
        circle.setOptions({
          fillColor: new Microsoft.Maps.Color(fillOpacity, 66, 133, 244),
          strokeColor: new Microsoft.Maps.Color(strokeOpacity, 66, 133, 244),
        });
      } else {
        this.bingMap.entities.remove(circle);
      }
    }, 400);
  }

  normalizeBoundaries(boundaries) {
    if (boundaries instanceof GeolocationBoundaries) {
      return boundaries;
    }

    if (boundaries instanceof Microsoft.Maps.LocationRect) {
      if (boundaries.getNorthwest().latitude === boundaries.getSoutheast().latitude && boundaries.getNorthwest().longitude === boundaries.getSoutheast().longitude) {
        return null;
      }
      return new GeolocationBoundaries({
        north: boundaries.getNorthwest().latitude,
        east: boundaries.getNorthwest().longitude,
        south: boundaries.getSoutheast().latitude,
        west: boundaries.getSoutheast().longitude,
      });
    }

    return false;
  }

  denormalizeBoundaries(boundaries) {
    if (boundaries instanceof Microsoft.Maps.LocationRect) {
      return boundaries;
    }

    if (boundaries instanceof GeolocationBoundaries) {
      return Microsoft.Maps.LocationRect.fromEdges(boundaries.north, boundaries.west, boundaries.south, boundaries.east);
    }

    return false;
  }

  addControl(element) {
    // TODO
  }

  removeControls() {
    // TODO
  }

  addAccuracyIndicatorCircle(coordinates, accuracy) {
    const path = Microsoft.Maps.SpatialMath.getRegularPolygon(new Microsoft.Maps.Location(coordinates.lat, coordinates.lng), accuracy, 36, Microsoft.Maps.SpatialMath.Meters);
    const circle = new Microsoft.Maps.Polygon(path, {
      fillColor: new Microsoft.Maps.Color(42, 66, 133, 244),
      strokeColor: new Microsoft.Maps.Color(85, 66, 133, 244),
      strokeThickness: 1,
    });
    this.bingMap.entities.push(circle);

    return circle;
  }
}