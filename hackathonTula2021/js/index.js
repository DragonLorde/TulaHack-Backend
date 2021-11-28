const wrapper = document.querySelector('.wrapper');
const fMenu = document.querySelector('.f-menu');
const menuIcon = document.querySelector('.menu__icon');
const header = document.querySelector('.header');

wrapper.addEventListener('click', (event) => {
	if (event.target.closest('.menu__container')) {
		fMenu.classList.toggle('hide');
		menuIcon.classList.toggle('active');
		header.classList.toggle('active');
	}
});

function renderProducts(data) {
	const stock = document.querySelector('.stock');
	for (let i = 0; i != 4; i++) {
		stock.insertAdjacentHTML('afterbegin', `
			<a href="/" class="list__item item">
				<div class="item__container">
					<div class="item__column item-col">
						<div class="item-col__row item-col-row">
							<div class="item-col-row__status">
								<p>${data.data.productData.promoProducts[i].promoProcent} %</p>
							</div>

							<div class="item-col-row__favorite">
								<img src="./src/img/icon/favorite.png" alt="">
							</div>
						</div>

						<div class="item-col__preview">
							<img src="${data.data.productData.promoProducts[i].img}" alt="">
						</div>

						<div class="item-col__title">
							<p>${data.data.productData.promoProducts[i].title}</p>
						</div>

						${makeService(data.data.productData.promoProducts[i].deliveryData).outerHTML}
						
						</div>
					</div>
				</div>
			</a>
		`)
		function makeService(data) {

			let diiv = document.createElement("div")

			data.forEach(el => {
				let div = document.createElement("div")
				div.className = 'item-col__row item-col-row'

				let div2 = document.createElement("div")
				div2.className = 'item-col-row-col__old-price'
				let p = document.createElement("p")

				let div3 = document.createElement("div")
				div3.className = 'item-col-row-col__current-price'
				let p1 = document.createElement("p")

				let service = document.createElement("div")
				let prices = document.createElement("div")
				let cartButton = document.createElement("div")
				
				service.className = 'item-col-row__service'
				prices.className = 'item-col-row__col item-col-row-col'
				cartButton.className = 'item-col-row__cart-button cart-button'
				
				
				let img = document.createElement('img')
				let img1 = document.createElement('img')
				let img2 = document.createElement('img')
				img.src = el.img
				img1.src = '../src/img/icon/plus.png'
				img2.src = '../src/img/icon/minus.png'
				
				console.log(el);

				div.append(img)
		
				service.append(img)
				div.append(service)
				

				p.insertAdjacentText('afterbegin', `${parseInt(parseInt(el.price) + Math.random(15) * 100)} ₽`)
				p1.insertAdjacentText('afterbegin', `${el.price} ₽`)
				div2.append(p)
				div3.append(p1)
				prices.append(div2)
				prices.append(div3)
				div.append(prices)
				
				img1.className = 'add-image hide'
				img2.className = 'remove-image'
				cartButton.append(img1)
				cartButton.append(img2)
				div.append(cartButton)

				diiv.append(div)
			})
		
			return diiv
		}
	}

}


function search() {
	window.location.pathname = '/search?'
}

function renderDelivery (data) {
	const top = document.querySelector('.top');

	for (let l = 0; l!= 4; l++) {
		top.insertAdjacentHTML('afterbegin', `
			<a href="/" class="list__item item">
				<div class="item__container">
					<div class="item__column item-col">
						<div class="item-col__row item-col-row">

						</div>

						<div class="item-col__preview">
							<img src="${data.deliveryData.BestDelivery[l].icon}" alt="">
						</div>

						<div class="item-col__title title-delivery">
							<p>${data.deliveryData.BestDelivery[l].title}</p>
						</div>

						
						
						<div class="item-col-row">
							<div class='item-col-row-col__current-price delivery-text'>
								<p>Средняя стоимость доставки: <span class="b">${data.deliveryData.BestDelivery[l].avgPriceDelivery}</span></p>
							</div>

						</div>

							<div class="item-col-row-col__current-price delivery-text">
								<p>Среднее время доставки: <span class="b">${data.deliveryData.BestDelivery[l].TimeAvg}</span></p>
							</div>


						</div>
					</div>
				</div>
			</a>
		`)
	}
}