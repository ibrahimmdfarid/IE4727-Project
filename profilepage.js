let initialValues = {};

function toggleEditMode() {
    const isReadOnly = document.getElementById("name").readOnly;

    // Store initial values if entering edit mode
    if (isReadOnly) {
        initialValues.name = document.getElementById("name").value;
        initialValues.email = document.getElementById("email").value;
        initialValues.address = document.getElementById("address").value;
    }

    // Toggle read-only state and button visibility
    document.querySelectorAll(".input-field").forEach(input => input.readOnly = !isReadOnly);
    document.getElementById("editButton").style.display = isReadOnly ? "none" : "inline-block";
    document.getElementById("saveButton").style.display = isReadOnly ? "inline-block" : "none";
    document.getElementById("cancelButton").style.display = isReadOnly ? "inline-block" : "none";
}

function cancelEdit() {
    // Revert to the initial values if canceling
    document.getElementById("name").value = initialValues.name;
    document.getElementById("email").value = initialValues.email;
    document.getElementById("address").value = initialValues.address;
    toggleEditMode();
}
