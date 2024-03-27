import { GeolocationMapMarker } from "../../../js/Base/GeolocationMapMarker.js";

/**
 * @prop {ymaps.Placemark} yandexMarker
 * @prop {Yandex} map
 */
export class YandexMapMarker extends GeolocationMapMarker {
  constructor(coordinates, settings = {}, map = null) {
    super(coordinates, settings, map);

    let properties = {
      hintContent: this.settings.title,
    };

    if (this.settings.label) {
      properties.iconContent = this.settings.label;
    }

    if (this.settings.icon) {
      properties = {
        iconLayout: "default#image",
        iconImageHref: this.settings.icon,
        iconImageSize: [30, 42],
        iconImageOffset: [-5, -38],
      };
    }

    const options = {};

    if (this.settings.draggable) {
      options.draggable = true;
    }

    this.yandexMarker = new ymaps.Placemark([parseFloat(coordinates.lat), parseFloat(coordinates.lng)], properties, options);

    this.yandexMarker.events.add("click", () => {
      this.click();
    });

    if (this.settings.draggable) {
      this.yandexMarker.events.add("dragend", (e) => {
        const coords = e.get("target").geometry.getCoordinates();
        this.update(this.map.normalizeCoordinates(coords));
      });
    }
  }

  update(newCoordinates, settings) {
    super.update(newCoordinates, settings);

    const currentCoordinates = this.map.normalizeCoordinates(this.yandexMarker.geometry.getCoordinates());

    if (newCoordinates) {
      if (!newCoordinates.equals(currentCoordinates.lat, currentCoordinates.lng)) {
        this.yandexMarker.geometry.setCoordinates(this.map.denormalizeCoordinates(newCoordinates));
      }
    }

    if (this.settings.title) {
      this.yandexMarker.options.set("title", this.settings.title);
    }
    if (this.settings.label) {
      this.yandexMarker.options.set("label", this.settings.label);
    }
    if (this.settings.icon) {
      this.yandexMarker.options.set("iconImageHref", this.settings.icon);
    }
  }

  remove() {
    super.remove();

    this.map.yandexMap.geoObjects.remove(this.yandexMarker);
  }
}
