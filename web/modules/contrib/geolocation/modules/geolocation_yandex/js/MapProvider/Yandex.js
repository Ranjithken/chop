import { GeolocationMapBase } from "../../../../js/MapProvider/GeolocationMapBase.js";
import { GeolocationBoundaries } from "../../../../js/Base/GeolocationBoundaries.js";
import { GeolocationCoordinates } from "../../../../js/Base/GeolocationCoordinates.js";
import { YandexMapMarker } from "../YandexMapMarker.js";

/* global CustomControlClass */

/**
 * @typedef YandexMapSettings
 *
 * @extends GeolocationMapSettings
 *
 * @prop {MapOptions} yandex_settings
 * @prop {Number} yandex_settings.zoom
 * @prop {Number} yandex_settings.max_zoom
 * @prop {Number} yandex_settings.min_zoom
 */

/**
 * @prop {ymaps.Map} yandexMap
 * @prop {YandexMapSettings} settings
 */
export default class Yandex extends GeolocationMapBase {
  constructor(mapSettings) {
    super(mapSettings);

    this.customControls = [];

    // Set the container size.
    this.container.style.height = this.settings.yandex_settings.height;
    this.container.style.width = this.settings.yandex_settings.width;
  }

  initialize() {
    return super
      .initialize()
      .then(() => {
        return new Promise((resolve) => {
          ymaps.ready(() => {
            resolve();
          });
        });
      })
      .then(() => {
        return new Promise((resolve) => {
          this.yandexMap = new ymaps.Map(
            this.container,
            {
              center: [0, 0],
              zoom: this.settings.yandex_settings.zoom,
              controls: [],
            },
            {
              maxZoom: this.settings.yandex_settings.max_zoom ?? 20,
              minZoom: this.settings.yandex_settings.min_zoom ?? 0,
            }
          );

          resolve();
        }).then(() => {
          return new Promise((resolve) => {
            let singleClick;

            this.yandexMap.events.add("click", (e) => {
              singleClick = setTimeout(() => {
                this.features.forEach((feature) => {
                  feature.onClick(this.normalizeCoordinates(e.get("coords")));
                });
              }, 500);
            });

            this.yandexMap.events.add("dblclick", (e) => {
              clearTimeout(singleClick);
              this.features.forEach((feature) => {
                feature.onDoubleClick(this.normalizeCoordinates(e.get("coords")));
              });
            });

            this.yandexMap.events.add("contextmenu", (e) => {
              this.features.forEach((feature) => {
                feature.onContextClick(this.normalizeCoordinates(e.get("coords")));
              });
            });

            this.yandexMap.events.add("boundschange", () => {
              this.updatingBounds = false;

              this.features.forEach((feature) => {
                feature.onMapIdle();
              });
            });

            this.yandexMap.events.add("boundschange", () => {
              const bounds = this.yandexMap.getBounds();
              if (!bounds) {
                return;
              }

              this.features.forEach((feature) => {
                feature.onBoundsChanged(this.normalizeBoundaries(bounds));
              });
            });

            resolve(this);
          });
        });
      });
  }

  createMarker(coordinates, settings) {
    const marker = new YandexMapMarker(coordinates, settings, this);
    this.yandexMap.geoObjects.add(marker.yandexMarker);

    return marker;
  }

  createShapeLine(geometry, settings) {
    const shape = super.createShapeLine(geometry, settings);

    shape.yandexShapes = [];

    const points = [];
    geometry.points.forEach((value) => {
      points.push([value.lat, value.lng]);
    });

    const line = new ymaps.Polyline(
      points,
      {
        hintContent: settings.title,
      },
      {
        strokeColor: settings.strokeColor,
        strokeOpacity: parseFloat(settings.strokeOpacity),
        strokeWidth: parseInt(settings.strokeWidth),
      }
    );

    this.yandexMap.geoObjects.add(line);

    shape.yandexShapes.push(line);

    return shape;
  }

  createShapePolygon(geometry, settings) {
    const shape = super.createShapePolygon(geometry, settings);

    shape.yandexShapes = [];

    const points = [];
    geometry.points.forEach((value) => {
      points.push([value.lat, value.lng]);
    });

    const polygon = new ymaps.Polygon(
      [points],
      {
        hintContent: settings.title,
      },
      {
        strokeColor: settings.strokeColor,
        strokeOpacity: parseFloat(settings.strokeOpacity),
        strokeWidth: parseInt(settings.strokeWidth),
        fillColor: settings.fillColor,
        fillOpacity: parseFloat(settings.fillOpacity),
      }
    );

    this.yandexMap.geoObjects.add(polygon);

    shape.yandexShapes.push(polygon);

    return shape;
  }

  createShapeMultiLine(geometry, settings) {
    const shape = super.createShapeMultiLine(geometry, settings);

    shape.yandexShapes = [];

    shape.geometry.lines.forEach((lineGeometry) => {
      const points = [];
      lineGeometry.points.forEach((value) => {
        points.push([value.lat, value.lng]);
      });

      const line = new ymaps.Polyline(points, {
        strokeColor: settings.strokeColor,
        strokeOpacity: parseFloat(settings.strokeOpacity),
        strokeWeight: parseInt(settings.strokeWidth),
      });

      if (settings.title) {
        this.addTitleToShape(line, settings.title);
      }

      this.yandexMap.geoObjects.add(line);

      shape.yandexShapes.push(line);
    });

    return shape;
  }

  createShapeMultiPolygon(geometry, settings) {
    const shape = super.createShapeMultiPolygon(geometry, settings);

    shape.yandexShapes = [];
    shape.geometry.polygons.forEach((polygonGeometry) => {
      const points = [];
      polygonGeometry.points.forEach((value) => {
        points.push([value.lat, value.lng]);
      });

      const polygon = new ymaps.Polygon(
        [points],
        {
          hintContent: settings.title,
        },
        {
          strokeColor: settings.strokeColor,
          strokeOpacity: parseFloat(settings.strokeOpacity),
          strokeWidth: parseInt(settings.strokeWidth),
          fillColor: settings.fillColor,
          fillOpacity: parseFloat(settings.fillOpacity),
        }
      );

      this.yandexMap.geoObjects.add(polygon);

      shape.yandexShapes.push(polygon);
    });

    return shape;
  }

  removeShape(shape) {
    if (!shape) {
      return;
    }

    if (shape.yandexShapes) {
      shape.yandexShapes.forEach((yandexShape) => {
        yandexShape.remove();
      });
    }

    shape.remove();
  }

  getBoundaries() {
    super.getBoundaries();

    return this.normalizeBoundaries(this.yandexMap.getBounds());
  }

  getShapeBoundaries(shapes) {
    super.getShapeBoundaries(shapes);

    shapes = shapes || this.dataLayers.get("default").shapes;
    if (!shapes.length) {
      return null;
    }

    const shapeBounds = [];

    shapes.forEach((shape) => {
      shape.yandexShapes.forEach((yandexShape) => {
        shapeBounds.push(yandexShape.geometry.getBounds());
      });
    });

    return this.normalizeBoundaries(ymaps.util.bounds.fromBounds(shapeBounds));
  }

  getMarkerBoundaries(markers) {
    super.getMarkerBoundaries(markers);

    markers = markers || this.dataLayers.get("default").markers;
    if (!markers) {
      return null;
    }

    const points = [];

    markers.forEach((marker) => {
      points.push(marker.yandexMarker.geometry.getCoordinates());
    });

    return this.normalizeBoundaries(ymaps.util.bounds.fromPoints(points));
  }

  setBoundaries(boundaries) {
    if (super.setBoundaries(boundaries) === false) {
      return false;
    }

    return this.yandexMap.setBounds(this.denormalizeBoundaries(boundaries));
  }

  getZoom() {
    this.yandexMap.getZoom();
  }

  setZoom(zoom, defer) {
    if (!zoom) {
      zoom = this.settings.yandex_settings.zoom;
    }
    zoom = parseInt(zoom);

    return this.yandexMap.setZoom(zoom);
  }

  getCenter() {
    return this.normalizeCoordinates(this.yandexMap.getCenter());
  }

  setCenterByCoordinates(coordinates, accuracy) {
    super.setCenterByCoordinates(coordinates, accuracy);

    this.yandexMap.panTo(this.denormalizeCoordinates(coordinates));
  }

  normalizeCoordinates(coordinates) {
    if (coordinates instanceof GeolocationCoordinates) {
      return coordinates;
    }

    return new GeolocationCoordinates(coordinates[0], coordinates[1]);
  }

  denormalizeCoordinates(coordinates) {
    if (!(coordinates instanceof GeolocationCoordinates)) {
      return coordinates;
    }

    return [coordinates.lat, coordinates.lng];
  }

  normalizeBoundaries(boundaries) {
    if (boundaries instanceof GeolocationBoundaries) {
      return boundaries;
    }

    return new GeolocationBoundaries({
      north: boundaries[1][1],
      east: boundaries[1][0],
      south: boundaries[0][1],
      west: boundaries[0][0],
    });
  }

  denormalizeBoundaries(boundaries) {
    if (boundaries instanceof GeolocationBoundaries) {
      return [
        [boundaries.west, boundaries.south],
        [boundaries.east, boundaries.north],
      ];
    }

    return false;
  }

  addControl(element) {
    const control = new CustomControlClass(element);
    this.customControls.push(control);
    this.yandexMap.controls.add(control, {
      float: "none",
      position: {
        top: 10,
        left: 10,
      },
    });
  }

  removeControls() {
    this.customControls.forEach((control) => {
      this.yandexMap.controls.remove(control);
    });
  }
}
