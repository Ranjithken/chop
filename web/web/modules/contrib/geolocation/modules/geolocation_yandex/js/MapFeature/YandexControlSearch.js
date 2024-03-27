import { YandexMapFeature } from "./YandexMapFeature.js";

export default class YandexControlSearch extends YandexMapFeature {
  constructor(settings, map) {
    super(settings, map);

    this.map.yandexMap.controls.add(
      new ymaps.control.SearchControl({
        options: { noPlacemark: true },
      })
    );
  }
}
