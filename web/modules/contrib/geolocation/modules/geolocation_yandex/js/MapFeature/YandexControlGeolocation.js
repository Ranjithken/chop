import { YandexMapFeature } from "./YandexMapFeature.js";

export default class YandexControlGeolocation extends YandexMapFeature {
  constructor(settings, map) {
    super(settings, map);

    this.map.yandexMap.controls.add(
      new ymaps.control.GeolocationControl({
        options: { noPlacemark: true },
      })
    );
  }
}
