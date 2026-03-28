function toggleMenu() {
   document.getElementById('isToggle').classList.toggle('open');
   var isOpen = document.getElementById('navigation')
   if (isOpen.style.display === "block") {
      isOpen.style.display = "none";
   } else {
      isOpen.style.display = "block";
   }
}

function activateMenu() {
   var menuItems = document.getElementsByClassName("sub-menu-item");
   if (menuItems) {

      var matchingMenuItem = null;
      for (var idx = 0; idx < menuItems.length; idx++) {
         if (menuItems[idx].href === window.location.href) {
            matchingMenuItem = menuItems[idx];
         }
      }

      if (matchingMenuItem) {
         matchingMenuItem.classList.add('active');
         var immediateParent = getClosest(matchingMenuItem, 'li');
         if (immediateParent) {
            immediateParent.classList.add('active');
         }

         var parent = getClosest(matchingMenuItem, '.parent-menu-item');
         if (parent) {
            parent.classList.add('active');
            var parentMenuitem = parent.querySelector('.menu-item');
            if (parentMenuitem) {
               parentMenuitem.classList.add('active');
            }
            var parentOfParent = getClosest(parent, '.parent-parent-menu-item');
            if (parentOfParent) {
               parentOfParent.classList.add('active');
            }
         } else {
            var parentOfParent = getClosest(matchingMenuItem, '.parent-parent-menu-item');
            if (parentOfParent) {
               parentOfParent.classList.add('active');
            }
         }
      }
   }
}

//Menu Active
function getClosest(elem, selector) {

   // Element.matches() polyfill
   if (!Element.prototype.matches) {
      Element.prototype.matches =
         Element.prototype.matchesSelector ||
         Element.prototype.mozMatchesSelector ||
         Element.prototype.msMatchesSelector ||
         Element.prototype.oMatchesSelector ||
         Element.prototype.webkitMatchesSelector ||
         function (s) {
            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
               i = matches.length;
            while (--i >= 0 && matches.item(i) !== this) { }
            return i > -1;
         };
   }

   // Get the closest matching element
   for (; elem && elem !== document; elem = elem.parentNode) {
      if (elem.matches(selector)) return elem;
   }
   return null;

}

// Clickable Menu
if (document.getElementById("navigation")) {
   document
      .querySelectorAll('#navigation .has-submenu > a')
      .forEach(function (link) {

         link.addEventListener('click', function (e) {

            if (window.innerWidth > 991) return;

            const parentLi = this.closest('.has-submenu');
            const submenu  = parentLi.querySelector('.submenu');

            if (!parentLi.classList.contains('open')) {
               e.preventDefault();

               parentLi.classList.add('open');
               if (submenu) submenu.classList.add('open');
            }
         });
      });
}

//  Window scroll sticky class add
function windowScroll() {
   const navbar = document.getElementById("topnav") || document.getElementById('navbar');
   if (
      document.body.scrollTop >= 50 ||
      document.documentElement.scrollTop >= 50
   ) {
      navbar.classList.add("nav-sticky");
   } else {
      navbar.classList.remove("nav-sticky");
   }
}
window.addEventListener("scroll", (ev) => {
   ev.preventDefault();
   windowScroll();
});

const backToTopButton = document.getElementById("back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
   scrollFunction();
};

function scrollFunction() {
   if (
      document.body.scrollTop > 100 ||
      document.documentElement.scrollTop > 100
   ) {
      backToTopButton.style.display = "block";
   } else {
      backToTopButton.style.display = "none";
   }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
   document.body.scrollTop = 0;
   document.documentElement.scrollTop = 0;
}

AOS.init();


try {
   const menu = [];
   const interleaveOffset = 0.5;
   const swiperOptions = {
      loop: true,
      speed: 1000,
      parallax: true,
      autoplay: {
         delay: 6500,
         disableOnInteraction: false,
      },
      watchSlidesProgress: true,
      pagination: {
         el: '.swiper-pagination',
         clickable: true,
         renderBullet: function (index, className) {
            return '<span class="' + className + '">' + 0 + (index + 1) + '</span>';
         },
      },

      navigation: {
         nextEl: '.swiper-button-next',
         prevEl: '.swiper-button-prev',
      },

      on: {
         progress: function () {
            var swiper = this;
            for (var i = 0; i < swiper.slides.length; i++) {
               const slideProgress = swiper.slides[i].progress;
               const innerOffset = swiper.width * interleaveOffset;
               const innerTranslate = slideProgress * innerOffset;
               swiper.slides[i].querySelector(".slide-inner").style.transform =
                  "translate3d(" + innerTranslate + "px, 0, 0)";
            }
         },

         touchStart: function () {
            const swiper = this;
            for (var i = 0; i < swiper.slides.length; i++) {
               swiper.slides[i].style.transition = "";
            }
         },

         setTransition: function (speed) {
            const swiper = this;
            for (var i = 0; i < swiper.slides.length; i++) {
               swiper.slides[i].style.transition = speed + "ms";
               swiper.slides[i].querySelector(".slide-inner").style.transition =
                  speed + "ms";
            }
         }
      }
   };

   // DATA BACKGROUND IMAGE
   const swiper = new Swiper(".swiper-container", swiperOptions);

   let data = document.querySelectorAll(".slide-bg-image")
   data.forEach((e) => {
      e.style.backgroundImage =
         `url(${e.getAttribute('data-background')})`;
   })
} catch (err) {

}

// const notyf = new Notyf({
//    duration: 5000,
//    position: {
//       x: 'right',
//       y: 'top',
//    },
//    types: [
//       {
//          type: 'success',
//          background: 'green',
//          dismissible: true,
//          icon: false,
//       },
//       {
//          type: 'info',
//          background: 'blue',
//          dismissible: true,
//          icon: false,
//       },
//       {
//          type: 'warning',
//          background: 'orange',
//          dismissible: true,
//          icon: false,
//       },
//       {
//          type: 'error',
//          background: 'indianred',
//          duration: 2000,
//          dismissible: true,
//          icon: false,
//       }
//    ]
// });
