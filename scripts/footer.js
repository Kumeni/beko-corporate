let  email;
const handleEmailChange = (event) => {
    email = event.target.value;
}

const subscribeToOurEmail = event => {
    event.preventDefault();

    /**
     * Form validation
     */
    if(email == "" || email == undefined){
        return;
    }
    /**
     * Sending the email to the server
     */
    let formData = new FormData();
    formData.append("email", email);


    const API_ENDPOINT = "./beko-corporate-admin/server/subscribe-email.php";
    const request = new XMLHttpRequest();

    request.open("POST", API_ENDPOINT, true);
    request.onreadystatechange = () => {
        if(request.readyState === 4 && request.status === 200){
            updateAllCategories(categories);
            updateButton("SUCCESSFULL");
            setTimeout(() => {
                updateButton("DONE");
            }, 1000);
            
            //get the active categoryFrom the categories/
            //get its parent
            //updateAllCategories(getCategoryFromTree(activeCategories));
            //updatePrimaryCategories(categories);
        } else {
            updateButton("FAILED");
            setTimeout(() => {
                updateButton("DONE");
            }, 1000);
        }
    }
    updateButton("PROCESSING");
    request.send(formData);
}

const updateButton = status => {
    let subscribeButton = document.getElementById("subscribe");

    if(status == "DONE"){
        subscribeButton.style.backgroundColor = "#14A44D";
        subscribeButton.value = "SUBSCRIBE";
    } else if(status == "PROCESSING"){
        subscribeButton.style.backgroundColor = "#3B71CA";
        subscribeButton.value = "SUBSCRIBING";
    } else if(status == "SUCCESSFUL"){
        subscribeButton.style.backgroundColor = "#14A44D";
        subscribeButton.value = "SUBSCRIBED";
    } else if(status == "FAILED"){
        subscribeButton.style.backgroundColor = "red";
        subscribeButton.value = "FAILED";
    }
}