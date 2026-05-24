import gsap from "gsap";

export class ScrollAnimations {
  static init() {
    this.revealUp();
    this.revealDown();
    this.revealLeft();
    this.revealRight();
    this.fadeIn();
    this.zoomIn();
    this.rise(); // новый эффект
  }

  static revealUp() {
    gsap.utils.toArray(".gsap-up").forEach((el) => {
     const offset = el.dataset.offset || 50;

      gsap.from(el, {
        y: 35,        
        opacity: 0.35,
        duration: 2,
        ease: "expo.out",
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });   
    });
  }

  static revealDown() {
    gsap.utils.toArray(".gsap-down").forEach((el) => {
      const offset = el.dataset.offset || 50;
      const delay = parseFloat(el.dataset.delay) || 0;

      gsap.from(el, {
        y: -100,
        opacity: 0,
        duration: 0.8,
        ease: "expo.out",
        delay: delay,
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });
    });
  }

  static revealLeft() {
    gsap.utils.toArray(".gsap-left").forEach((el) => {
      const offset = el.dataset.offset || 50;

      gsap.from(el, {
        x: -100,
        opacity: 0,
        duration: 0.8,
        ease: "expo.out",
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });
    });
  }

  static revealRight() {
    gsap.utils.toArray(".gsap-right").forEach((el) => {
      const offset = el.dataset.offset || 50;

      gsap.from(el, {
        x: 100,
        opacity: 0,
        duration: 0.8,
        ease: "expo.out",
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });
    });
  }

  static fadeIn() {
    gsap.utils.toArray(".gsap-fadein").forEach((el) => {
      const offset = el.dataset.offset || 50;

      gsap.from(el, {
        opacity: 0,
        duration: 0.8,
        ease: "expo.out",
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });
    });
  }

  static zoomIn() {
    gsap.utils.toArray(".gsap-zoomin").forEach((el) => {
      const offset = el.dataset.offset || 50;

      gsap.from(el, {
        scale: 0.85,
        opacity: 0,
        duration: 0.8,
        ease: "expo.out",
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });
    });
  }

  static rise() {
    gsap.utils.toArray(".gsap-rise").forEach((el) => {
      const offset = el.dataset.offset || 50;

      gsap.from(el, {
        y: 90,
        opacity: 0,
        duration: 0.8,
        ease: "expo.out",
        scrollTrigger: {
          trigger: el,
          start: "top 95%",
          toggleActions: "play none none none",
          fastScrollEnd: true,
        },
      });
    });
  }
}
