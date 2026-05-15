let calculatorItems = {
    transport: [],
    goods: [],
    services: []
};

function loadSavedData() {
    const saved = localStorage.getItem('calculatorItems');
    if (saved) {
        try {
            calculatorItems = JSON.parse(saved);
        } catch (e) {
            console.error('Ошибка загрузки сохранённых данных');
        }
    }
}

function addToCart(category, itemId) {
    console.log("Item ID: "+itemId);
    let data_send = {};
    if (category === 'transport') {
        data_send = {"type": "getVehicleById", "id": itemId};
    }else if (category === 'goods') {
        data_send = {"type": "getGoodsById", "id": itemId};
    }else if (category === 'services') {
        data_send = {"type": "getServiceById", "id": itemId};
    }else {
        console.error(`Неизвестная категория: ${category}`);
        return;
    }
    $.post("../server/post/userCalculatorHandler.php", data_send, function (data){
        const response = JSON.parse(data);
        if (response.response) {
            let product = JSON.parse(response.response.message);
            console.log(product);
            const existingIndex = calculatorItems[category].findIndex(i => i.id === product.id);
            let difference = 0;
            if (existingIndex !== -1) {
                let newQuantity = calculatorItems[category][existingIndex].quantity + 1;
                difference = product.available_stock - newQuantity;
                if (difference === 0) {
                    Toast.success("Добавлено в корзину!");
                    calculatorItems[category][existingIndex].quantity += 1
                    calculatorItems[category][existingIndex].is_full = true;
                }else if (difference === -1) {
                    Toast.warning("Вы добавили максимальное количество!");
                    calculatorItems[category][existingIndex].is_full = true;
                }else{
                    Toast.success("Добавлено в корзину!");
                    calculatorItems[category][existingIndex].quantity += 1
                }
            } else {
                difference = product.available_stock - 1;
                if (difference === 0) {
                    Toast.success("Добавлено в корзину!");
                    calculatorItems[category].push({
                        ...product,
                        quantity: 1,
                        is_full: true
                    });
                }else{
                    Toast.success("Добавлено в корзину!");
                    calculatorItems[category].push({
                        ...product,
                        quantity: 1,
                        is_full: false
                    });
                }
            }
            localStorage.setItem('calculatorItems', JSON.stringify(calculatorItems));
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadSavedData();
});