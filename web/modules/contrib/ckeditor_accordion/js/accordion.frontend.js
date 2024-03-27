/**
 * @file
 * CKEditor Accordion functionality.
 */

(function ($, Drupal, once, drupalSettings) {
  "use strict";

  let animating = false;
  let animateObjects = [];

  // Default config.
  const defaults = {
    duration: 300,
    easing: (t, b, c, d) => {
      if ((t /= d / 2) < 1) return (c / 2) * t * t + b;
      return (-c / 2) * (--t * (t - 2) - 1) + b;
    },
  };

  // Two directions.
  const directions = {
    OPEN: 1,
    CLOSE: 2,
  };

  // Animate fn.
  const animate = (animateSets, now) => {
    let animationContinue = false;
    for (let x = 0; x < animateSets.length; x++) {
      let element = animateSets[x][0];
      let options = animateSets[x][1];
      if (!options.startTime) {
        options.startTime = now;
      }
      let currentTime = now - options.startTime;
      animationContinue = currentTime < options.duration;
      if (animationContinue) {
        let newHeight = options.easing(
          currentTime,
          options.startingHeight,
          options.distanceHeight,
          options.duration
        );
        element.style.height = `${newHeight.toFixed(2)}px`;
      }
    }

    if (animationContinue) {
      window.requestAnimationFrame((timestamp) =>
        animate(animateSets, timestamp)
      );
    } else {
      for (let x = 0; x < animateSets.length; x++) {
        let element = animateSets[x][0];
        let options = animateSets[x][1];
        if (options.direction === directions.CLOSE) {
          element.style.display = "none";
        }
        if (options.direction === directions.OPEN) {
          element.style.display = "block";
        }

        // Remove element animation styles.
        element.style.height = null;
        element.style.overflow = null;
        element.style.marginTop = null;
        element.style.marginBottom = null;
        element.style.paddingTop = null;
        element.style.paddingBottom = null;
      }

      // Reset vars.
      animateObjects = [];
      animating = false;
    }
  };

  // Animation handler.
  const doAnimations = (animateObjects) => {
    let doAnimate =
      drupalSettings.ckeditorAccordion.accordionStyle
        .animateAccordionOpenAndClose ?? true;
    if (!doAnimate) {
      animateObjects.forEach((object) => {
        const element = object[1];
        if (object[0] == "slideUp") {
          element.style.display = "none";
        } else {
          element.style.display = "block";
        }
      });
      animating = false;
    } else {
      let animateSets = [];
      animateObjects.forEach((object) => {
        const element = object[1];
        let options = Object.assign({}, defaults);
        if (object[0] == "slideUp") {
          options.direction = directions.CLOSE;
          options.to = 0;
          options.startingHeight = element.scrollHeight;
          options.distanceHeight = -options.startingHeight;

          // Set element animation styles.
          element.style.display = "block";
          element.style.overflow = "hidden";
          element.style.marginTop = "0";
          element.style.marginBottom = "0";
          element.style.paddingTop = "0";
          element.style.paddingBottom = "0";
        } else {
          element.style.height = "0px";

          // Set element animation styles.
          element.style.display = "block";
          element.style.overflow = "hidden";
          element.style.marginTop = "0";
          element.style.marginBottom = "0";
          element.style.paddingTop = "0";
          element.style.paddingBottom = "0";

          options.direction = directions.OPEN;
          options.to = element.scrollHeight;
          options.startingHeight = 0;
          options.distanceHeight = options.to;
        }

        delete options.startTime;
        animateSets.push([element, options]);
      });

      window.requestAnimationFrame((timestamp) =>
        animate(animateSets, timestamp)
      );
    }
  };

  Drupal.behaviors.ckeditorAccordion = {
    attach: function (context, settings) {
      const $ckeditorAccordions = $(".ckeditor-accordion");
      var $length;
      const doAnimate =
        drupalSettings.ckeditorAccordion.accordionStyle
          .animateAccordionOpenAndClose ?? true;
      const openTabsWithHash =
        drupalSettings.ckeditorAccordion.accordionStyle.openTabsWithHash ?? " ";
      for (let i = 0; i < $ckeditorAccordions.length; i++) {
        let $accordion = $ckeditorAccordions[i];

        // The first one is the correct one.
        if (!drupalSettings.ckeditorAccordion.accordionStyle.collapseAll) {
          $accordion.querySelector("dt:first-child").classList.add("active");
          let dd = $accordion.querySelector("dd:first-of-type");
          dd.classList.add("active");
          dd.style.display = "block";
        }

        // Turn the accordion tabs to links so that the content is accessible & can be traversed using keyboard.
        let childDts = Array.from($accordion.children).filter(function (child) {
          return child.tagName.toLowerCase() == "dt";
        });
        $(childDts).each(function (idx, el) {
          // $accordion.children('dt').each(function () {
          var $tab = $(this);
          var tabText = $tab.html().trim();
          var toggleClass = $tab.hasClass("active") ? " active" : "";
          $tab.html(
            '<div class="ckeditor-accordion-toggler "><div class="row"><span class="ckeditor-accordion-toggle col-1 customtoggle' +
            toggleClass +
            '"></span><span class="col-11 m-0 p-0">' +
            tabText +
            "</span></div></div>"
          );
        });
        // for (let x = 0; x < childDts.length; x++) {
        //   let $tab = childDts[x];
        //   let tabText = $tab.innerText.trim();
        //   let toggleClass = $tab.classList.contains("active") ? " active" : "";
        //   let hrefAndIds = 'href="#"';
        //   if (openTabsWithHash) {
        //     let tabHash = encodeURIComponent(
        //       tabText.replace(/[^A-Za-z0-9]/g, "")
        //     );
        //     hrefAndIds =
        //       'href="#' +
        //       tabHash +
        //       '" id="' +
        //       tabHash +
        //       '" onclick="return false;"';
        //   }
        //   $tab.innerHTML =
        //     '<div class="ckeditor-accordion-toggler "><div class="row"><span class="ckeditor-accordion-toggle col-1 customtoggle' +
        //     toggleClass +
        //     '"></span><span class="col-11 m-0 p-0">' +
        //     tabText +
        //     "</span></div></div>";
        //   // $tab.innerHTML = '<a class="ckeditor-accordion-toggler" ' + hrefAndIds + '><span class="ckeditor-accordion-toggle' + toggleClass + '"></span>' + tabText + '</a>';
        // }

        // Wrap the accordion in a div element so that quick edit function shows the source correctly.
        $accordion.classList.add("styled");
        $accordion.classList.remove("ckeditor-accordion");
        let $wrapper = document.createElement("div");
        $wrapper.classList.add("ckeditor-accordion-container");
        if (!doAnimate) {
          $wrapper.classList.add("no-animations");
        }
        $accordion.after($wrapper);
        $wrapper.appendChild($accordion);

        // Trigger an ckeditorAccordionAttached event to let other frameworks know that the accordion has been attached.
        let eventAccordionAttached = new Event("ckeditorAccordionAttached");
        $accordion.dispatchEvent(eventAccordionAttached);

        // $(".ckeditor-accordion-container").each(function () {
        //   $length = $(this).children().children("dt").length;
        //   console.log($length);
        //   if ($length != 1) {
        //     $(this).prepend(
        //       '<div class="expandall"> <span> Expand All </span> </div>'
        //     );
        //   } else if ($length == 1) {
        //     $(this).addClass("pt-0").css("margin-top", "5px");
        //   }
        // });
        $(".ckeditor-accordion-container").each(function (e) {
          $length = $(this).children().children("dt").length;
          console.log($(".ckeditor-accordion-container"));
          console.log($length);
          if ($length != 1) {
            $(this).find(".expandall").remove();
            $(this).prepend(
              '<div class="expandall"> <span> Expand All </span> </div>'
            );
          } else if ($length == 1) {
            $(this).addClass("pt-0").css("margin-top", "5px");
          }
          return;
        });

        $("body").off("click", ".expandall").on("click", ".expandall", function (e) {
          e.preventDefault();
          var $current = $(this);
          var $child = $current.next();
          var $subchild = $(this);
          var $collapse = $(".collapseall");
          var $expandall = $(".expandall");

          $collapse
            .removeClass("collapseall")
            .addClass("expandall")
            .text("")
            .html("<span>Expand All</span>");
          if ($collapse.hasClass("active")) {
            e.preventDefault();
            $collapse
              .addClass("expandall")
              .removeClass("collapseall active")
              .text("Expand All")
              .next()
              .children("dd")
              .slideUp()
              .removeClass("active")
              .prev()
              .removeClass("active");
          }
          if ($current.hasClass("active")) {
            //$child.slidedown();
            $current.removeClass("active");
            $current.next().children().removeClass("active");
            //$current.next().children().slideUp();
          } else {
            $(".expandall").removeClass("active");
          }

          $current
            .addClass("collapseall active")
            .removeClass("expandall")
            .text("")
            .html("<span> Collapse All </span>");
          //$current.prepend('<div class="collapseall"> Collapse ALL</div>');
          $current.next().children()
          .slideDown(300)
          .addClass("active");
        });


        $("body").off("click", ".collapseall").on("click", ".collapseall", function (e) {
          e.preventDefault();
          var $current = $(this);
          var $child = $current.next();
          var $subchild = $(this);
          if ($current.hasClass("active")) {
            //$child.slidedown();
            // The first one is the correct one.
            $current.next().children().removeClass("active");
            $current.next().children().removeClass("active");

            $current.removeClass("active");
            $current.next().children().removeClass("active");
            //$current.next().children().slideUp();
          } else {
            $(".expandall").removeClass("active");
            // $current.children('dt.active').removeClass('active').children('a').removeClass('active');
            // $current.children('dd.active').slideUp(function () {
            // $(this).removeClass('active');
            // })
          }

          $current
            .addClass("expandall")
            .removeClass("collapseall active")
            .text("")
            .html("<span> Expand All </span>");
          //$current.next().children().slideUp(300);
          $current.next().children("dd").slideUp().removeClass("active");
          $current.next().children("dt").removeClass("active");
        });
        // Handle click events.
        let $togglers = $accordion.querySelectorAll(
          ".ckeditor-accordion-toggler .customtoggle"
        );
        // $("body").on(
        //   "click",
        //   ".ckeditor-accordion-toggler .customtoggle",
        $(
          once(
            "ckeditorAccordionToggleEvent",
            ".ckeditor-accordion-toggler .customtoggle"
          )
        ).click(function (e) {
          var $t = $(this).parent().parent().parent();
          var $parent = $t.parent();

          // Clicking on open element, close it.
          if ($t.hasClass("active")) {
            $t.removeClass("active");
            $t.next().slideUp();
            $("dt").each(function (index) {
              if ($("dt").hasClass("active")) {
              } else {
                console.log("inActive");

                $parent
                  .prev()
                  .addClass("expandall")
                  .removeClass("collapseall active")
                  .text("Expand All");
              }
            });
          } else {
            if (!drupalSettings.ckeditorAccordion.accordionStyle.keepRowsOpen) {
              // Remove active classes.
              $parent
                .children("dt.active")
                .removeClass("active")
                .children("a")
                .removeClass("active");
              $parent.children("dd.active").slideUp(function () {
                $(this).removeClass("active");
              });
            }

            // Show the selected tab.
            $t.addClass("active");
            $t.next().slideDown(300).addClass("active");
          }

          // Don't add hash to url.
          e.preventDefault();
        });

        // Open tabs with hash if config requires.
        if (openTabsWithHash) {
          // Trigger hash change when clicking an anchor to an accordion tab on the same page.
          const $hashLinks = document.querySelectorAll(
            'a[href*="#"]:not(.ckeditor-accordion-toggler):not(.visually-hidden)'
          );
          for (let x = 0; x < $hashLinks.length; x++) {
            $hashLinks[x].addEventListener("click", function (e) {
              var parser = document.createElement("a"),
                hash;
              var preventDefault = false;
              parser.href = this.getAttribute("href");
              hash = parser.hash;
              if (hash) {
                // Get the matching accordion toggler with hash.
                var el = document.querySelector(
                  'a.ckeditor-accordion-toggler[href="' + hash + '"]'
                );
                if (el != null) {
                  // Set / update hash so that the event listener below fires.
                  if (window.location.hash === hash) {
                    window.dispatchEvent(new Event("hashchange"));
                  } else {
                    window.location.hash = hash;
                  }

                  preventDefault = true;
                }
              }

              if (preventDefault) {
                e.preventDefault();
              }
            });
          }

          // Open content that matches the hash on hash change.
          window.addEventListener("hashchange", function () {
            var el = document.querySelector(
              'a.ckeditor-accordion-toggler[href="' +
              window.location.hash +
              '"]'
            );
            if (el != null && !el.parentNode.classList.contains("active")) {
              el.click();
            }
          });
          // Trigger event once on page load so that the right accordion is open.
          window.dispatchEvent(new Event("hashchange"));
        }
      }
    },
  };
})

  (jQuery, Drupal, once, drupalSettings);
