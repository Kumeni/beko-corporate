const checkJwt = () => {
    let jwt = localStorage.getItem("jwt");

    if(!jwt){
        location.replace("./auth/login.php");
        return;
    }

    /**
     * Make http request to check if jwt is good;
     */
    let formData = new FormData();

    formData.append("jwt", jwt);

    const API_ENDPOINT = "../api/validate-jwt.php";
    const request = new XMLHttpRequest();

    request.open("POST", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            let authorization = JSON.parse(request.response);
            localStorage.setItem("jwt", authorization.jwt);
        } else {
            //console.log(request.response);
            if(request.status == 400){
                localStorage.removeItem("jwt");
                location.replace("./auth/login.php");
            }
        }
    }

    request.send(formData);
}
//checkJwt();

const logout = () => {
    localStorage.removeItem("jwt");
    location.replace("./auth/login.php");
}
setInterval( checkJwt, 2000);