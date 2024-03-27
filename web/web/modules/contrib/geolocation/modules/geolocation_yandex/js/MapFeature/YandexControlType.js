import { YandexMapFeature } from "./YandexMapFeature.js";

export default class YandexControlType extends YandexMapFeature {
  constructor(settings, map) {
    super(settings, map);

    this.map.yandexMap.controls.add(new ymaps.control.TypeSelector());
  }
}
