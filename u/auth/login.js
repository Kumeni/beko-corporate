let email, code;

const handleEmailChange = event => {
    email=event.target.value;
    document.getElementById("email-error").innerHTML = "";
}

const getCode = event => {
    event.preventDefault();
    let canSubmit = true, formData = new FormData(), 
    handleSuccessElement = document.getElementById("code-sent-successfully"),
    handleErrorElement = document.getElementById("code-not-sent");

    if(email == undefined || email == ""){
        document.getElementById("email-error").innerHTML = "Email is required!";
        canSubmit = false;
    }

    if(!isValidEmail(email)){
        document.getElementById("email-error").innerHTML = "Invalid email!";
        canSubmit = false;
    }

    if(canSubmit){
        /**
         * Make http request;
         */
        const API_ENDPOINT = "../../api/get-login-code.php";
        const request = new XMLHttpRequest();

        formData.append("email", email);
        request.open("POST", API_ENDPOINT, true);
        request.onreadystatechange = () => {
            if(request.readyState === 4 && request.status === 200){
                console.log(request.response);
                handleSuccessElement.innerHTML = `Check your email, <b>${email}</b>, for your login code`;
                handleSuccessElement.style.display = "block";
                handleErrorElement.style.display = "none";
                document.getElementById("login-code").focus();
            } else {
                handleErrorElement.innerHTML = `Something went wrong, check your internet connection`;
                handleErrorElement.style.display = "block";
                handleSuccessElement.style.display = "none";
            }
        }
        request.send(formData);
    }
    
    
}

const login = event => {
    event.preventDefault();

    let canSubmit = true, formData = new FormData(), 
    codeErrorElement = document.getElementById("code-error");

    if(email == undefined || email == ""){
        document.getElementById("email-error").innerHTML = "Email is required!";
        canSubmit = false;
    }

    if(!isValidEmail(email)){
        document.getElementById("email-error").innerHTML = "Invalid email!";
        canSubmit = false;
    }

    if(code == undefined || code == ""){
        codeErrorElement.innerHTML = "Login Code is required!";
        canSubmit = false;
    }

    if(canSubmit){
        /**
         * Make http request;
         */
        const API_ENDPOINT = "../../api/validate-code.php";
        const request = new XMLHttpRequest();

        formData.append("email", email);
        formData.append("code", code);

        request.open("POST", API_ENDPOINT, true);
        request.onreadystatechange = () => {
            if(request.readyState === 4 && request.status === 200){

                //storing the jwt to localStorage
                let authorization = JSON.parse(request.response);
                localStorage.setItem("jwt", authorization.jwt);

                console.log(authorization.jwt);
                
                //redirecting to admin panel
                location.replace("../products.html");
            } else {

                let errorResponse = JSON.parse(request.response);
                if(errorResponse.error) codeErrorElement.innerHTML = errorResponse.error;
                else codeErrorElement.innerHTML = "";
            }
        }
        request.send(formData);
    }
}

const isValidEmail = email =>{
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

const handleCodeChange = event => {
    code = event.target.value;
    document.getElementById("code-error").innerHTML = ""
}