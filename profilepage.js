let isEditMode = false;  // Flag to track whether in edit mode or not
let initName = '';
let initAddress = '';
let initCardDetails = '';

// Set maximum length for Name
const MAX_NAME_LENGTH = 50;

// Function to validate Name
function validateName(name) {
    const namePattern = /^[A-Za-z\s]+$/; // Only letters and spaces allowed
    if (!name.match(namePattern)) {
        return 'Name must contain only letters and spaces.';
    }
    if (name.length > MAX_NAME_LENGTH) {
        return `Name cannot be longer than ${MAX_NAME_LENGTH} characters.`;
    }
    return true;
}

// Function to validate Card Details
function validateCardDetails(cardDetails) {
    const cardPattern = /^\d{16}$/;  // Only digits and exactly 16 digits
    if (!cardDetails.match(cardPattern)) {
        return 'Card details must be exactly 16 digits.';
    }
    return true;
}

// Function to validate Address
function validateAddress(address) {
    if (!address.trim()) {
        return 'Address cannot be empty.';
    }
    return true;
}

// Function to toggle edit mode
function toggleEditMode() {
    initName = document.getElementById('name').value;
    initAddress = document.getElementById('address').value;
    initCardDetails = document.getElementById('card_details').value;
    
    const inputs = document.querySelectorAll('.input-field');
    const editButton = document.getElementById('editButton');
    const cancelButton = document.getElementById('cancelButton');
    const saveButton = document.getElementById('saveButton');
    
    const emailInput = document.getElementById('email');
    emailInput.setAttribute('readonly', true);  // Ensure email remains readonly

    if (isEditMode) {  // If currently in edit mode
        inputs.forEach(input => input.setAttribute('readonly', true));
        editButton.style.display = 'inline-block';
        cancelButton.style.display = 'none';
        saveButton.style.display = 'none';
    } else {  // If currently not in edit mode
        inputs.forEach(input => {
            if (input !== emailInput) {  // Allow all fields except email to be editable
                input.removeAttribute('readonly');
            }
        });
        editButton.style.display = 'none';
        cancelButton.style.display = 'inline-block';
        saveButton.style.display = 'inline-block';
    }

    // Toggle the flag
    isEditMode = !isEditMode;
}

// Function to cancel editing and revert to original values
function cancelEdit() {
    // Revert back to original values if cancel is clicked
    document.getElementById('name').value = initName;
    document.getElementById('address').value = initAddress;
    document.getElementById('card_details').value = initCardDetails;
    toggleEditMode();  // Close edit mode
}

// Function to handle form submission
function validateForm(event) {
    // Get values from the form
    const name = document.getElementById('name').value;
    const address = document.getElementById('address').value;
    const cardDetails = document.getElementById('card_details').value;

    // Validate each field
    let isValid = true;
    let errorMessage = '';

    // Validate Name
    const nameValidation = validateName(name);
    if (nameValidation !== true) {
        isValid = false;
        errorMessage += nameValidation + '\n';
    }

    // Validate Address
    const addressValidation = validateAddress(address);
    if (addressValidation !== true) {
        isValid = false;
        errorMessage += addressValidation + '\n';
    }

    // Validate Card Details
    const cardValidation = validateCardDetails(cardDetails);
    if (cardValidation !== true) {
        isValid = false;
        errorMessage += cardValidation + '\n';
    }

    // If any validation fails, show alert and prevent form submission
    if (!isValid) {
        alert(errorMessage);
        event.preventDefault();  // Prevent form submission
    }
}

// Attach form validation to the form submit event
document.querySelector('form').addEventListener('submit', validateForm);
