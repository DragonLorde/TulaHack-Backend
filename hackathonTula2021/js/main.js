const cartButton = document.querySelectorAll('.cart-button');

for (let i = 0; i < cartButton.length; i++) {
	cartButton[i].addEventListener('click', (event) => {
		if (event.target.closest('.cart-button')) {
			event.preventDefault();
		}
	});
	
}

