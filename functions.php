<!-- // ______________________________ -->

<!-- [whatsapp_order_form] -->

function whatsapp_order_form_shortcode() {
    global $product;

    if ( ! is_product() || ! $product ) {
        return ''; // Ensure it's a product page and the product is valid
    }

    // Get product details
    $product_id = $product->get_id();
    $product_price = $product->get_price(); // Get the product price
    $product_name = $product->get_name(); // Get the product name

    // Set default shipping cost
    $default_shipping_cost = 180;
    $shipping_cost = $default_shipping_cost;
    $shipping_label = "Standard"; // Default shipping label

    ob_start();
    ?>
    <!-- Button to trigger the Bootstrap Modal -->
    <a href="#" class="btn btn-whatsapp" data-bs-toggle="modal" data-bs-target="#whatsappOrderModal">
        <i class="bi bi-whatsapp"></i> Order on WhatsApp
    </a>

    <!-- Bootstrap Modal for WhatsApp Order Form -->
    <div class="modal fade" id="whatsappOrderModal" tabindex="-1" aria-labelledby="whatsappOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whatsappOrderModalLabel">Order on WhatsApp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="whatsapp-order-form" method="POST">
                        <div class="mb-3">
                            <label for="whatsapp-name" class="form-label">Your Name:</label>
                            <input type="text" id="whatsapp-name" name="name" class="form-control" required placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp-phone" class="form-label">Your Phone Number:</label>
                            <input type="tel" id="whatsapp-phone" name="phone" class="form-control" required placeholder="Enter your phone number">
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp-address" class="form-label">Your Address:</label>
                            <input type="text" id="whatsapp-address" name="address" class="form-control" required placeholder="Enter your address">
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp-quantity" class="form-label">Quantity:</label>
                            <input type="number" id="whatsapp-quantity" name="quantity" value="1" min="1" class="form-control" required onchange="updateTotal()">
                        </div>

                        <!-- Shipping Options - Radio Buttons (Inline) -->
                        <label class="form-label">Shipping Option:</label>
                        <div class="mb-3 d-flex">
                            <div class="form-check me-3">
                                <input type="radio" name="shipping_option" value="180" class="form-check-input" checked onchange="updateTotal()">
                                <label class="form-check-label">Standard PKR-180</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="shipping_option" value="250" class="form-check-input" onchange="updateTotal()">
                                <label class="form-check-label">Express PKR-250</label>
                            </div>
                        </div>

                        <p><strong>Product Price: </strong>PKR <?php echo number_format($product_price, 2); ?></p>
                        <p><strong>Total: </strong><span id="total-cost">PKR <?php echo number_format($product_price + $shipping_cost, 2); ?></span></p>

                        <button type="submit" class="btn btn-success" id="submit-order">Send Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* WhatsApp Button Styling */
        .btn-whatsapp {
            background-color: #25D366; /* WhatsApp Green */
            color: white;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 16px;
            height: var(--btn-height);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
        }

        .btn-whatsapp i {
            margin-right: 10px;
        }

        .modal-header {
            background-color: var(--color-gray-100);
            border-bottom: 1px solid var(--color-gray-300);
        }

        .modal-title {
            color: var(--color-gray-900);
            font-weight: 600;
        }

        .modal-body {
            background-color: var(--color-white);
        }

        .form-label {
            color: var(--color-gray-800);
        }

        .form-control {
            border-radius: var(--wd-brd-radius);
            border: 1px solid var(--wd-form-brd-color);
            height: var(--wd-form-height);
        }

        .form-control:focus {
            border-color: var(--wd-form-brd-color-focus);
            box-shadow: none;
        }

        .btn {
            background-color: #007bff;
            color: white;
            text-transform: var(--btn-transform);
            font-weight: var(--btn-font-weight);
            font-size: 16px;
            height: var(--btn-height);
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        // Ensure Bootstrap Modal works correctly
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('whatsappOrderModal'));
            var triggerButton = document.querySelector('[data-bs-toggle="modal"]');
            triggerButton.addEventListener('click', function() {
                modal.show();
            });
        });

        // Handle form submission
        document.getElementById('whatsapp-order-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Gather form data
            var name = document.getElementById('whatsapp-name').value;
            var phone = document.getElementById('whatsapp-phone').value;
            var address = document.getElementById('whatsapp-address').value;
            var quantity = document.getElementById('whatsapp-quantity').value;
            var shipping_cost = document.querySelector('input[name="shipping_option"]:checked').value;
            var shipping_label = document.querySelector('input[name="shipping_option"]:checked').nextElementSibling.textContent.trim(); // Get the shipping option label
            var product = "<?php echo $product_name; ?>"; // Get the product title dynamically
            var product_price = <?php echo $product_price; ?>; // Get the product price dynamically

            // Calculate total cost
            var total_cost = (product_price * quantity) + parseFloat(shipping_cost);

            // WhatsApp message format
            var message = `Order Details:
Hello, I would like to order the following product:

Product: ${product}

Name: ${name}
Phone: ${phone}
Address: ${address}
Quantity: ${quantity}
Price: PKR ${product_price}
Shipping: ${shipping_label} 

*Total: PKR ${total_cost.toFixed(2)}*`;

            // Open WhatsApp link
            var whatsappUrl = `https://wa.me/923044627811?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        });

        // Update total cost dynamically
        function updateTotal() {
            var quantity = document.getElementById('whatsapp-quantity').value;
            var shipping_cost = document.querySelector('input[name="shipping_option"]:checked').value;
            var product_price = <?php echo $product_price; ?>;
            var total_cost = (product_price * quantity) + parseFloat(shipping_cost);
            document.getElementById('total-cost').textContent = "PKR " + total_cost.toFixed(2);
        }
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('whatsapp_order_form', 'whatsapp_order_form_shortcode');

function enqueue_bootstrap() {
    // Enqueue Bootstrap CSS and JS for the modal functionality
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js', array(), null, true);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js', array('popper-js'), null, true);

    // Enqueue Bootstrap Icons (for WhatsApp icon)
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');

