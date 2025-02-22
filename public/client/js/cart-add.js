let cartCount = 0;
let cart = {};

window.addEventListener("scroll", function () {
    let cartIcon = document.getElementById("cartIcon");
    cartIcon.style.display = window.scrollY >= 0 ? "block" : "none";
});

document.querySelectorAll(".add-cart").forEach(button => {
    button.addEventListener("click", function (event) {
        let productId = this.getAttribute("data-id");
        addToCart(productId, event);
    });
});

function addToCart(productId, event) {
    // console.log(productId);
    fetch(`cart/add/${productId}/1`, {
        method: "GET",
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("cartCount").innerText = data.cart_count;
            animateToCart(event);
            shakeCartIcon();
            let cartList = document.getElementById("cartList");
            document.querySelector('.cart-quantity-header').textContent = data.cart_product_count;
            let item = data.cart.items[productId];
            console.log(data.cart.items[productId]);
            if (item) {
                let oldValue = document.querySelector(`.cart-item-quantity-${item.id}`);
                console.log(oldValue);
                if (oldValue === null) {
                    let cartItem = document.createElement("div");
                    cartItem.classList.add("cart-item");
                    cartItem.classList.add("p-1");
                    priceProduct = formatNumber(item.price);
                    cartItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <img src="uploads/${item.image}" alt="${item.name}" class="cart-item-img" width="50">
                            <div class="d-inline-block flex-col">
                                <span>${item.name}</span> </br>
                                <span class="font-weight-bold">${priceProduct} đ</span>
                            </div>
                        </div>
                        <span class="cart-item-quantity-${item.id} quantity-badge">1</span>
                    </div>`;
                    cartList.appendChild(cartItem);
                }
                else oldValue.textContent = parseInt(oldValue.textContent) + 1;
                // else {
                // }
            }
            // data.cart.items.forEach(item => {
            //     console.log(item)
            // });
        } else {
            console.log("Thêm vào giỏ hàng thất bại!");
        }
    })
    .catch(error => console.error("Lỗi fetch:", error));
}

function formatNumber(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function toggleCart() {
    let cartItems = document.getElementById("cartItems");
    cartItems.style.display = cartItems.style.display === "none" ? "block" : "none";
    // loadCartItems(); //Cập nhật giỏ hàng khi mở
}


function animateToCart(event) {
    let cartIcon = document.getElementById("cartIcon");
    let productElement = event.target.closest(".product__item").querySelector(".set-bg");
    let imageUrl = productElement.getAttribute("data-setbg");

    let flyingImg = document.createElement("img");
    flyingImg.src = imageUrl;
    flyingImg.classList.add("fly-to-cart");
    document.body.appendChild(flyingImg);
    console.log(productElement, imageUrl, flyingImg);


    let productRect = event.target.getBoundingClientRect();
    let cartRect = cartIcon.getBoundingClientRect();

    flyingImg.style.left = `${productRect.left + window.scrollX}px`;
    flyingImg.style.top = `${productRect.top + window.scrollY}px`;

    setTimeout(() => {
        flyingImg.style.transform = `translate(${cartRect.left - productRect.left}px, ${cartRect.top - productRect.top}px) scale(0)`;
        flyingImg.style.opacity = "0";
    }, 100);

    setTimeout(() => {
        flyingImg.remove();
        cartIcon.classList.add("shake");
        setTimeout(() => cartIcon.classList.remove("shake"), 500);
    }, 800);
}

function shakeCartIcon() {
    let cartIcon = document.getElementById("cartIcon");
    cartIcon.classList.add("shake");
    setTimeout(() => cartIcon.classList.remove("shake"), 500);
}

function goToCartPage() {
    window.location.href = "/cart";
}
