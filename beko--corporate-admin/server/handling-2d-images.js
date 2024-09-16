// Your 2D array of images
let imageArray = [
    [image1, image2],
    [image3, image4]
];

// Create a FormData object
let formData = new FormData();

// Flatten the 2D array and append images to formData
imageArray.forEach((row, i) => {
    row.forEach((image, j) => {
        formData.append(`image_${i}_${j}`, image); // Append each image with a unique key
    });
});

// Send the formData via fetch or XMLHttpRequest
fetch('your-server-endpoint', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
