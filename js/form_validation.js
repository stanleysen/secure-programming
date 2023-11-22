// manual validasi input buat email
function validateForm() {
    var email = document.getElementById("email").value;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    return true;
}
function validateFormPass() {
    var password = document.getElementById("password").value;
    var passwordRegex = /^(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])(?=.*[0-9])(?=.*[A-Z]).{8,}$/;
    // /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/
    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long and contain at least one special character, one number, and one uppercase letter.");
        return false;
    }
    return true;
}