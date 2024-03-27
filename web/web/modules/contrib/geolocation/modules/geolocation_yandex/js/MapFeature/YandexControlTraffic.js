import { YandexMapFeature } from "./YandexMapFeature.js";

export default class YandexControlTraffic extends YandexMapFeature {
  constructor(settings, map) {
    super(settings, map);

    this.map.yandexMap.controls.add("trafficControl");
  }
}
