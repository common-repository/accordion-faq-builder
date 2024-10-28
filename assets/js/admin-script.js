var aFaqBuilder = aFaqBuilder || {};
aFaqBuilder.counter = 0;

aFaqBuilder.addNewFaqItem = {
	init: function () {
		this.create();
	},

	create: function () {
		const addNewItem = document.getElementById("add-new-faq-item");

		// Exit immediately if not right place
		if (!addNewItem) return;

		const afbItems = document.querySelector("#afbItems");

		// count total child elements
		aFaqBuilder.counter = parseInt(addNewItem.getAttribute("data-next"));

		addNewItem.addEventListener("click", function (e) {
			e.preventDefault();
			aFaqBuilder.cloneIt(aFaqBuilder.counter, afbItems);
		});

		// Shortable.js
		new Sortable(afbItems, {
			animation: 150,
			handle: ".handle",
			ghostClass: "blue-background-class",
		});

		// Move UP, Down, Expand
		aFaqBuilderControls();
	},
};

aFaqBuilder.cloneIt = function (counter, parentEl, element = false) {
	const node = element ? element : document.getElementById("clonable-item");
	const clone = node.cloneNode(true);

	// Set new attributes
	clone.setAttribute("data-id", counter);
	clone.setAttribute("id", "item-" + counter);
	clone.setAttribute("class", "afb--item afb--item-" + counter + " expanded");
	clone.getElementsByTagName("h3")[0].innerHTML =
		AFB_Admin_DATA.new_item_text;

	const new_id_for_title = "afb_data[contents][" + counter + "][title]";
	const title_label_selector = clone.querySelectorAll(
		'[data-target="title-label"]'
	);
	const title_input_selector = clone.querySelectorAll(
		'[data-target="title-input"]'
	);
	title_label_selector[0].setAttribute("for", new_id_for_title);
	title_input_selector[0].setAttribute("id", new_id_for_title);
	title_input_selector[0].setAttribute("name", new_id_for_title);

	const new_id_for_content = "afb_data[contents][" + counter + "][content]";
	const content_label_selector = clone.querySelectorAll(
		'[data-target="content-label"]'
	);
	const content_input_selector = clone.querySelectorAll(
		'[data-target="content-input"]'
	);
	content_label_selector[0].setAttribute("for", new_id_for_content);
	content_input_selector[0].setAttribute("id", new_id_for_content);
	content_input_selector[0].setAttribute("name", new_id_for_content);
	if (element) {
		// clone existing node
		parentEl.insertBefore(clone, element.nextSibling);
	} else {
		// add new node by cloned clonable node
		parentEl.appendChild(clone);
	}
	aFaqBuilder.counter++;
};

aFaqBuilder.copyShortcode = {
	init: function () {
		this.create();
	},

	create: function () {
		const elements = document.querySelectorAll(
			".post-type-accordion_faq .shortcode code"
		);
		if (elements < 1) return;
		elements.forEach((el, i) => {
			el.addEventListener("click", function () {
				for (const item of elements) {
					if (item.classList.contains("copied")) {
						item.classList.remove("copied");
					}
				}
				el.classList.add("copied");
				aFaqBuilder.copyShortcode.selecttext(el.getAttribute("id"));
			});
		});
	},

	// https://stackoverflow.com/questions/1173194/select-all-div-text-with-single-mouse-click
	selecttext: function (containerid) {
		if (document.selection) {
			// IE
			var range = document.body.createTextRange();
			range.moveToElementText(document.getElementById(containerid));
			range.select();
			this.selecttextanimation(containerid);
		} else if (window.getSelection) {
			var range = document.createRange();
			range.selectNode(document.getElementById(containerid));
			window.getSelection().removeAllRanges();
			window.getSelection().addRange(range);
			navigator.clipboard.writeText(
				document.getElementById(containerid).innerText
			);
			this.selecttextanimation(containerid);
		}
	},

	/**
	 * Copy animation
	 *
	 * @since 0.1
	 */
	selecttextanimation: function (containerid) {
		const shortcodeContainer = document.getElementById(containerid);
		const afbCurrentNotifyContainer = shortcodeContainer.nextElementSibling;
		const afbAllNotifyContainer = document.querySelectorAll(
			".post-type-accordion_faq .shortcode .afb-notify"
		);

		afbAllNotifyContainer.forEach((el, i) => {
			if (el.classList.contains("show")) {
				el.classList.remove("show");
			}
		});
		afbCurrentNotifyContainer.classList.add("show");
	},
};

aFaqBuilder.toggleIt = {
	init: function () {
		this.toggle();
	},

	toggle: function () {
		const expandBtn = document.querySelector(".afb_data_expand_all");
		const faqItems = document.querySelectorAll(".afb--items .afb--item");
		expandBtn.addEventListener("click", function () {
			faqItems.forEach((el) => {
				el.classList.add("expanded");
			});
		});

		const collapseBtn = document.querySelector(".afb_data_collapse_all");
		collapseBtn.addEventListener("click", function () {
			faqItems.forEach((el) => {
				el.classList.remove("expanded");
			});
		});
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
function afbDomReady(fn) {
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

afbDomReady(function () {
	aFaqBuilder.addNewFaqItem.init();
	aFaqBuilder.copyShortcode.init();
	aFaqBuilder.toggleIt.init();
});

/**
 * Helper functions
 *
 * @since 0.1
 */
function aFaqBuilderControls() {
	const parentEl = document.querySelector("#afbItems");
	const afbItemsAll = afbItems.querySelectorAll(".afb--item");
	afbItemsAll.forEach((el, i) => {
		el.querySelector(".afb--items .item-header .afb--rs").addEventListener(
			"click",
			function (e) {
				if (e.target.classList.contains("move-down"))
					moveDown(this.parentNode.parentNode.parentNode);
				else if (e.target.classList.contains("move-up"))
					moveUp(this.parentNode.parentNode.parentNode);
				else if (e.target.classList.contains("expand-handle"))
					expand(this.parentNode.parentNode.parentNode);
				else if (e.target.classList.contains("clone"))
					aFaqBuilder.cloneIt(
						aFaqBuilder.counter,
						parentEl,
						this.parentNode.parentNode.parentNode
					);
				else if (e.target.classList.contains("trash"))
					trash(this.parentNode.parentNode.parentNode);
			}
		);
	});

	function moveUp(element) {
		if (element.previousElementSibling)
			element.parentNode.insertBefore(
				element,
				element.previousElementSibling
			);
	}
	function moveDown(element) {
		if (element.nextElementSibling)
			element.parentNode.insertBefore(
				element.nextElementSibling,
				element
			);
	}
	function expand(element) {
		if (!element.classList.contains("expanded")) {
			element.classList.toggle("expanded");
		} else {
			element.classList.toggle("expanded");
		}
	}

	function trash(element) {
		element.remove();
	}
}
