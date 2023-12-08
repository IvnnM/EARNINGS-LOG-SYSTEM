// Function to handle the login action
function handleLogin() {
    const username = document.getElementById('inputUsername').value;
    const password = document.getElementById('inputPassword').value;

    // Check if any of the required fields is empty
    if (!username || !password) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields!',
        });
        return;
    }

    const data = {
        username: username,
        password: password
    };

    executeLogin(data);
}

// Function to execute the login action
function executeLogin(data) {
    const url = 'php/login_handler.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data),
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Log the result from the server
        if (result === 'success') {
             // Clear input boxes
            document.getElementById('inputUsername').value = '';
            document.getElementById('inputPassword').value = '';
            
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: 'Login successful!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Redirect to another page or perform additional actions
                window.location.href = 'index.php';
            });
        } else {
            document.getElementById('inputPassword').value = '';
            // Show SweetAlert for authentication failure or other errors
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result || 'Unknown error occurred',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors if any
    });
}


// Function to handle adding stock
function addStock(productId, productName) {
    Swal.fire({
        title: `Enter quantity to add Stocks for ${productName}:`,
        input: 'number',
        inputAttributes: {
            min: 1,
        },
        showCancelButton: true,
        confirmButtonText: 'Add',
        showLoaderOnConfirm: true,
        preConfirm: (quantity) => {
            // Execute the addStock action with the entered quantity
            const data = {
                action: 'addStock',
                product_id: productId,
                quantity: quantity,
            };
            executeAddStock(data);
        },
        allowOutsideClick: () => !Swal.isLoading(),
    });
}

// Function to execute the addStock action
function executeAddStock(data) {
    const url = 'php/product_process.php'; // Change the URL to match your structure

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Log the result from the server
        if (result === 'success') {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: 'Stock added successfully!',
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                // Reload the page after the SweetAlert is closed
                location.reload();
            });

        } else {
            // Handle other cases if needed
            console.log('Add stock failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors if any
    });
}

// Function to handle the product action (add/update/delete)
function handleProductAction(action) {
    const productId = document.getElementById('productProductId').value;
    const productName = document.getElementById('productProductName').value;
    const unitPrice = document.getElementById('productUnitPrice').value;
    const stockQuantity = document.getElementById('productStockQuantity').value;

    // Check if any of the required fields is empty or below 0
    if ((action !== 'addProduct' && !productId) || !productName || !unitPrice || !stockQuantity || unitPrice < 0 || stockQuantity < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields with valid values!',
        });
        return;
    }

    const data = {
        action: action,
        product_id: productId,
        product_name: productName,
        unit_price: unitPrice,
        stock_quantity: stockQuantity
    };

    Swal.fire({
        title: `Are you sure you want to ${action.toUpperCase()}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${action.toUpperCase()}`,
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            executeProductAction(action, data);
        }
    });
}

// Function to execute the product action (add/update/delete)
function executeProductAction(action, data) {
    const url = 'php/product_process.php'; // Change the URL to match your structure

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Log the result from the server
        if (result === 'success') {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: `${action.toUpperCase()} successfully!`,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Reload the page after the SweetAlert is closed
                location.reload();
            });

            // Clear input boxes
            document.getElementById('productProductId').value = '';
            document.getElementById('productProductName').value = '';
            document.getElementById('productUnitPrice').value = '';
            document.getElementById('productStockQuantity').value = '';
        } else if (result === 'exists') {
            // Show SweetAlert for existing product
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Product with the same name already exists!',
            });
        } else {
            // Handle other cases if needed
            console.log(`${action.toUpperCase()} failed`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors if any
    });
}

/////////////////////////////////////////////////////
// Function to handle the cart action (add/delete)
function handleCartAction(action) {
    const cartProductId = document.getElementById('cartProductId').value;
    const cartProductName = document.getElementById('cartProductName').value;
    const cartUnitPrice = document.getElementById('cartUnitPrice').value;
    const cartSoldQuantity = document.getElementById('cartSoldQuantity').value;

    // Check if any of the required fields is empty or below 0
    if (!cartProductId || !cartProductName || !cartUnitPrice || cartUnitPrice < 0 || cartSoldQuantity < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields with valid values!',
        });
        return;
    }

    const data = {
        action: action,
        product_id: cartProductId,
        unit_price: cartUnitPrice,
        quantity: cartSoldQuantity
    };

    Swal.fire({
        title: `Are you sure you want to ${action.toUpperCase()}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${action.toUpperCase()}`,
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            executeCartAction(action, data);
        }
    });
}

// Function to execute the cart action (add/delete)
function executeCartAction(action, data) {
    
    const url = 'php/cart_handler.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Log the result from the server
        if (result === 'success') {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: `${action.toUpperCase()} successfully!`,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Reload the page after the SweetAlert is closed
                location.reload();
            });

            // Clear input boxes
            document.getElementById('cartProductId').value = '';
            document.getElementById('cartProductName').value = '';
            document.getElementById('cartUnitPrice').value = '';
            document.getElementById('cartSoldQuantity').value = '';
        } else {
            // Show SweetAlert for item not found or other errors
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result || 'Unknown error occurred',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors if any
    });
}
/////////////////////////////////////////
// Function to handle the submit cart action
// Function to handle the submit cart action
function handleSubmitCart() {
    // Get the selected date from the input field
    const selectedDate = document.getElementById('selectedDate').value;

    // Check if a date is selected
    if (!selectedDate) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select a date!',
        });
        return false; // Prevent form submission
    }

    // Confirm with the user before submitting the cart
    Swal.fire({
        title: 'Submit Report?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            // Make a request to your PHP script to handle the cart submission
            fetch('./php/submit_cart.php?selectedDate=' + selectedDate)
                .then(response => response.text())
                .then(result => {
                    // Log the result from the server
                    console.log(result);

                    // Check for 'error' in the response
                    if (result === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Report submitted successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Reload the page after the SweetAlert is closed
                            location.reload();
                        });
                        // Optionally, you can clear the cart or take other actions
                    } else if (result === 'Cart is empty.') {
                        // Show cart is empty message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Cart is empty.',
                        });
                    } else if (result === 'Error executing the cart query.') {
                        // Show error executing cart query message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error executing the cart query.',
                        });
                    } else {
                        // Show other error messages
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result || 'Unknown error occurred',
                        });
                        // Optionally, you can clear the cart or take other actions
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle errors if any
                });
        }
    });

    return false; // Prevent form submission
}

