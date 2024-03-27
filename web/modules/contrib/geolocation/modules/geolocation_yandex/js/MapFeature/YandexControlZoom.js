import { YandexMapFeature } from "./YandexMapFeature.js";

export default class YandexControlZoom extends YandexMapFeature {
  constructor(settings, map) {
    super(settings, map);

    let options = {};

    switch (this.settings.position) {
      case "left":
      case "top":
      case "bottom":
        // Leave the default values.
        options = {};
        break;
      case "right":
        // Size adaptivity will be disabled.
        options = {
          position: {
            top: "108px",
            right: "10px",
            bottom: "auto",
            left: "auto",
          },
        };
        break;

      default:
        throw new Error("Yandex: Unknown control position.");
    }

    this.map.yandexMap.controls.add("zoomControl", options);
  }
}
