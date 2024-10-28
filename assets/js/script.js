var AFBFront = AFBFront || {};

AFBFront.accordion = {
	init: function () {
		this.beforeAnimate();
		this.create();
	},

	create: function () {
		const afb_boxs = document.querySelectorAll(".a-faq-builder .afb-items");
		afb_boxs.forEach((afb_box) => {
			const afb_items = afb_box.querySelectorAll("li.afb-item");
			afb_items.forEach((el, i) => {
				const afb_item_title = el.querySelector("a.afb-item-title");
				afb_item_title.addEventListener("click", function (event) {
					event.preventDefault();
					if (!AFB_DATA.multi_open) {
						for (const item of afb_items) {
							if (
								el !== item &&
								item.classList.contains("active")
							) {
								item.classList.remove("active");
							}
						}
					}
					el.classList.toggle("active");
					AFBFront.accordion.startAnimate(afb_items);
				});
			});
			AFBFront.accordion.startAnimate(afb_items);
		});

		// On screen resize fixed accordion body height
		window.addEventListener("resize", function () {
			AFBFront.accordion.beforeAnimate();
		});
	},

	beforeAnimate: function () {
		const items = document.querySelectorAll(".a-faq-builder .afb-items");
		items.forEach((el, i) => {
			const items = el.querySelectorAll(".afb-item");
			items.forEach((childEl, j) => {
				const parentEl = childEl.querySelector(".afb-item-body");
				const elHeight = childEl.querySelector(".afb-item-content");
				parentEl.setAttribute("max-height", elHeight.offsetHeight);
			});
		});
	},

	startAnimate: function (afb_items) {
		for (const item of afb_items) {
			const targetEl = item.querySelector(".afb-item-body");
			const elHeight = targetEl.getAttribute("max-height");

			if (item.classList.contains("active")) {
				if (parseFloat(elHeight) <= 500) {
					targetEl.style.maxHeight = parseFloat(elHeight) + 30 + "px";
				} else {
					targetEl.style.maxHeight = 500 + "px";
					targetEl.style.overflow = "auto";
				}
			} else {
				targetEl.style.maxHeight = "";
			}
		}
	},
};

/**
 * Is the DOM ready?
 *
 * This implementation is coming from https://gomakethings.com/a-native-javascript-equivalent-of-jquerys-ready-method/
 *
 * @since 0.1
 *
 * @param {Function} fn Callback function to run.
 */
function AFBFrontDomReady(fn) {
	if (typeof fn !== "function") {
		return;
	}

	if (
		document.readyState === "interactive" ||
		document.readyState === "complete"
	) {
		return fn();
	}

	document.addEventListener("DOMContentLoaded", fn, false);
}

AFBFrontDomReady(function () {
	AFBFront.accordion.init();
});
