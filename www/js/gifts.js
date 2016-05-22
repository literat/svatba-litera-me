const Gifts = {

	addEvent(obj, evt, callback) {
		if (obj.addEventListener) {
			obj.addEventListener(evt, callback);
		} else if (obj.attachEvent) {
			obj.attachEvent('on' + evt, callback);
		}
	},

	handleButton(item, type) {
		var id = item.id;
		var text = item.getElementsByTagName('span')[0].innerHTML;

		this.send2form(id, text, type);
	},

	send2form(id, text, type) {
		var form = document.getElementById('contact-form');
		var subject = document.getElementById('frm-subject');
		var message = document.getElementById('frm-message');
		var hidden = document.getElementById('frm-gifts');

		if (type == 'bring') {
			var subjectText = 'Přivezu jídlo!';
		} else {
			var subjectText = 'Rád/a pomůžu!';
		}

		subject.value = subjectText;
		message.innerText = text;
		hidden.value = id;
	},

	init() {
		var bringButtons = document.getElementsByClassName('btn bring');
		var willButtons = document.getElementsByClassName('btn will');

		for (var i = 0; i < bringButtons.length; i++) {
			this.addEvent(bringButtons[i], 'click', function(event) {
				var targetElement = event.target || event.srcElement;
				Gifts.handleButton(targetElement.parentElement, 'bring')
			});
		}

		for (var i = 0; i < willButtons.length; i++) {
			this.addEvent(willButtons[i], 'click', function(event) {
				var targetElement = event.target || event.srcElement;
				Gifts.handleButton(targetElement.parentElement, 'will')
			});
		}
	}

}

Gifts.init();
