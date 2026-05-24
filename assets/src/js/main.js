import "@/css/main.css";

/*======================================================================================================================
Basic Viewport 
========================================================================================================================*/
import { BaseHelpers } from "./utils/base-helpers";
BaseHelpers.addLoadedClass();
BaseHelpers.calcScrollbarWidth();
BaseHelpers.addTouchClass();

// Базовые скрипты для автомастабирования и больших экранов с dpr
import initAutoRem from "./utils/autorem";
const { scaleFactor, destroy } = initAutoRem({
  baseSiteWidth: 1536,
  baseFontSize: 16
});

import { initViewport } from "@/js/utils/viewport";
initViewport({
  breakpoint: 1536,
  designWidth: 1536
});

import '../../../components/ui';

/*======================================================================================================================
Gsap animation
========================================================================================================================*/
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { ScrollAnimations } from "./modules/scrollAnimation.js";

// gsap animation class init
window.addEventListener("load", () => {
  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    gsap.registerPlugin(ScrollTrigger);
    ScrollAnimations.init();
  }
});



/*======================================================================================================================
Main Javascript
========================================================================================================================*/
import { initPhoneMasks } from "./scripts/initPhoneMasks";

document.addEventListener("DOMContentLoaded", () => {
  initPhoneMasks();
});
