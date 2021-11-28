fetch('https://laboratory-msk.online/v1.0/getProductsFeed').then((response) => 
	response.json()
).then((data) => {
	console.log(data);
	renderProducts(data);
})

fetch('https://laboratory-msk.online/v1.0/getDeliveryBest').then((response) => 
	response.json()
).then((data) => {
	console.log(data);
	renderDelivery(data);
})

