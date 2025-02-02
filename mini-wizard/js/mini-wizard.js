document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".wizard-content");
    const errorMessagesContainer = document.getElementById("error-messages");
    let quantityValue = 0;

    function calculatePrice(quantity) {
        if (quantity >= 1 && quantity <= 10) {
            return 10;
        } else if (quantity >= 11 && quantity <= 100) {
            return 100;
        } else if (quantity >= 101 && quantity <= 1000) {
            return 1000;
        } else {
            return 0;
        }
    }

    function isValidEmail(email) {
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailPattern.test(email);
    }

    function isValidPhone(phone) {
        const phoneDigits = phone.replace(/[^\d+]/g, '');
        return (phoneDigits.startsWith('+380') && phoneDigits.length === 13) ||
            (phoneDigits.startsWith('0') && phoneDigits.length === 10);
    }

    function isValidName(name) {
        return name.trim().length > 0;
    }

    function isValidQuantity(quantity) {
        const number = parseInt(quantity, 10);
        return !isNaN(number) && number > 0 && number <= 1000;
    }

    function validateStep(step) {
        const nameInput = step.querySelector("#name");
        const emailInput = step.querySelector("#email");
        const phoneInput = step.querySelector("#phone");
        const quantityInput = step.querySelector("#quantity");
        const quantityError = step.querySelector("#quantity-error");

        errorMessagesContainer.innerHTML = "";
        let isValid = true;

        if (nameInput && !isValidName(nameInput.value)) {
            showError("Please enter a valid name.");
            isValid = false;
        }

        if (emailInput && !isValidEmail(emailInput.value.trim())) {
            showError("Please enter a valid email address.");
            isValid = false;
        }

        if (phoneInput && !isValidPhone(phoneInput.value.trim())) {
            showError("Please enter a valid phone number.");
            isValid = false;
        }

        if (quantityInput) {
            const quantity = quantityInput.value.trim();
            if (!isValidQuantity(quantity)) {
                quantityError.textContent = "Quantity must be a number between 1 and 1000.";
                quantityInput.classList.add("is-invalid");
                showError("Quantity must be between 1 and 1000.");
                isValid = false;
            } else {
                quantityValue = parseInt(quantity, 10);
                quantityError.textContent = "";
                quantityInput.classList.remove("is-invalid");
            }
        }
        return isValid;
    }

    function showError(message) {
        const errorMessage = document.createElement("div");
        errorMessage.className = "alert alert-danger";
        errorMessage.textContent = message;
        errorMessagesContainer.appendChild(errorMessage);
    }

    function sendEmail(data) {
        fetch("http://localhost:3000/send-email", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (response.ok) {
                    const currentStep = document.querySelector(".wizard-content.active");
                    const nextStep = currentStep?.nextElementSibling;
                    if (nextStep && nextStep.classList.contains("wizard-content")) {
                        currentStep.classList.remove("active");
                        nextStep.classList.add("active");
                        updateBreadcrumb(nextStep);

                    }
                } else {
                    const currentStep = document.querySelector(".wizard-content.active");
                    const errorStep = document.querySelector("#step-5");
                    if (errorStep) {
                        currentStep.classList.remove("active");
                        errorStep.classList.add("active");
                        updateBreadcrumb(errorStep);
                    }
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                const currentStep = document.querySelector(".wizard-content.active");
                const errorStep = document.querySelector("#step-5");
                if (errorStep) {
                    currentStep.classList.remove("active");
                    errorStep.classList.add("active");
                    updateBreadcrumb(errorStep);
                }
            });
    }

    const nextStepButtons = document.querySelectorAll('.next-step');
    const prevStepButtons = document.querySelectorAll('.prev-step');
    const sendEmailButton = document.getElementById('send-email-btn');

    function handleButtonClick(event) {
        const currentStep = document.querySelector(".wizard-content.active");
        const button = event.target;

        if (button.classList.contains("next-step")) {
            if (validateStep(currentStep)) {
                const nextStep = currentStep?.nextElementSibling;
                if (nextStep && nextStep.classList.contains("wizard-content")) {
                    currentStep.classList.remove("active");
                    nextStep.classList.add("active");
                    updateBreadcrumb(nextStep);

                    if (nextStep.id === "step-3") {
                        const quantityDisplay = nextStep.querySelector("#quantity-display");
                        if (quantityDisplay) {
                            const price = calculatePrice(quantityValue);
                            quantityDisplay.textContent = `$${price}`;
                        }
                    }
                }
            }
        } else if (button.classList.contains("prev-step")) {
            if (currentStep.id === "step-3") {
                currentStep.classList.remove("active");
                steps[1].classList.add("active");
                updateBreadcrumb(steps[1]);
            } else {
                const prevStep = currentStep?.previousElementSibling;
                if (prevStep && prevStep.classList.contains("wizard-content")) {
                    currentStep.classList.remove("active");
                    prevStep.classList.add("active");
                    updateBreadcrumb(prevStep);
                }
            }
        } else if (button.id === "send-email-btn") {
            const name = document.querySelector("#name").value.trim();
            const email = document.querySelector("#email").value.trim();
            const phone = document.querySelector("#phone").value.trim();
            const quantity = parseInt(document.querySelector("#quantity").value.trim(), 10);
            const price = calculatePrice(quantity);

            const emailData = {
                name: name,
                email: email,
                phone: phone,
                quantity: quantity,
                price: price,
            };
            sendEmail(emailData);
        }
        event.stopPropagation();
    }

    nextStepButtons.forEach(button => button.addEventListener("click", handleButtonClick));
    prevStepButtons.forEach(button => button.addEventListener("click", handleButtonClick));
    if (sendEmailButton) {
        sendEmailButton.addEventListener("click", handleButtonClick);
    }

    function updateBreadcrumb(step) {
        let stepNumber;
        let instanceCount;

        if (step && step.id) {
            let idParts = step.id.split("-");
            stepNumber = parseInt(idParts[1]);
            instanceCount = stepNumber;
        }
        const breadcrumbItems = document.querySelectorAll('.breadcrumb-item');
        breadcrumbItems.forEach(item => item.classList.remove('active'));

        if (stepNumber) {
            const breadcrumbItem = document.querySelector(`#breadcrumb-step-${stepNumber}-${instanceCount}`);
            if (breadcrumbItem) {
                breadcrumbItem.classList.add('active');
            }
        } else {
            breadcrumbItems[0].classList.add('active');
        }
    }

    const firstStep = document.querySelector("#step-1");
    if (firstStep) {
        updateBreadcrumb(firstStep);
    }
    const startAgainButtons = document.querySelectorAll('.start-again-btn');

    startAgainButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            setTimeout(() => {
                const form = button.closest("form");
                if (form) {
                    form.reset();
                }

                if (errorMessagesContainer) {
                    errorMessagesContainer.innerHTML = "";
                }

                const inputs = document.querySelectorAll("input");
                inputs.forEach(input => {
                    input.classList.remove("is-invalid");
                });
                steps.forEach(step => step.classList.remove("active"));
                const firstStep = document.querySelector("#step-1");
                if (firstStep) {
                    firstStep.classList.add("active");
                    updateBreadcrumb(firstStep);
                }
            }, 100);
            event.preventDefault();
        });
    });
});
