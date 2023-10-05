<!DOCTYPE html>
<html>
<head>
    <title>5-Star Rating System</title>
    <style>
        .rating {
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
        }

        .rating > span {
            display: inline-block;
            position: relative;
            width: 1.1em;
        }

        .rating > span:hover:before,
        .rating > span:hover ~ span:before {
            content: "\2605";
            position: absolute;
        }
    </style>
</head>
<body>
<h1>Rate this item:</h1>
<div class="rating" id="rating">
    <span data-rating="5">&#9733;</span>
    <span data-rating="4">&#9733;</span>
    <span data-rating="3">&#9733;</span>
    <span data-rating="2">&#9733;</span>
    <span data-rating="1">&#9733;</span>
</div>

<p>Your rating: <span id="selected-rating">0</span>/5</p>
<button id="submit-rating">Submit</button>

<script>
    const stars = document.querySelectorAll('.rating > span');
    const selectedRating = document.getElementById('selected-rating');
    const submitButton = document.getElementById('submit-rating');
    let userRating = 0;

    stars.forEach((star) => {
        star.addEventListener('click', (e) => {
            userRating = parseInt(e.target.getAttribute('data-rating'));
            updateRating();
        });
    });

    function updateRating() {
        selectedRating.innerText = userRating;
        stars.forEach((star) => {
            const rating = parseInt(star.getAttribute('data-rating'));
            if (rating <= userRating) {
                star.innerText = '\u2605'; // Filled star
            } else {
                star.innerText = '\u2606'; // Empty star
            }
        });
    }

    submitButton.addEventListener('click', () => {
        // Here, you can send the 'userRating' to your server to save the data.
        alert(`You rated this item ${userRating}/5. Data submitted successfully (dummy action).`);
    });
</script>
</body>
</html>
