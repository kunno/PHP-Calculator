// Retrieves all the keys on calc
var keys = document.querySelectorAll('#keys span');
// Retrieves screen div
var input = document.querySelector('#screen');
// Sets operators array
var operators = ['+', '−', 'x', '÷', '^'];
// Flag to track when decimal is added to screen
var decimalAdded = false;
// Retrieves the results div
var results = document.querySelector('#results');


// Clear button function to change text on click
$('#clear').click(function() {
    // Finds clear button and checks it's text
    if ($(this).find('span.clear').text() == 'AC') {
        $(this).find('span.clear').text('C');
    }

});

// Function to clear the screen
function clearScreen() {
    if($('#clear').find('span.clear').text() == 'AC') {
        input.innerHTML = '';
        decimalAdded = false;
    }
}

//Fixes bug that allows one or no operands to be evaluated upon first launch
function resetInput() {
    if (input.innerHTML.length == '21') {
        input.innerHTML = '';
    }
}
resetInput();

// Function to change clear button text
function changeClearText() {
    $('#clear').find('span.clear').text('AC');

}


// Add onclick event to all the keys
for(var i = 0; i < keys.length; i++) {
    keys[i].onclick = function(e) {
        // Get the button values
        var btnVal = this.innerHTML;
        // Variable to get screen text
        var inputVal = input.innerHTML;


        // Function to send the equation to server
        function sendEquation() {
            if (equation.indexOf('+')) {
                var temp = encodeURI(equation);
                temp = temp.replace('+', '%2B');
                equation = decodeURI(temp);
                window.location.href = "index.php?equate=" + equation;

            }
            else {
                window.location.href = "index.php?equate=" + equation;

            }
        }



        // If clear key is pressed, erase everything
        if(btnVal == 'C' || btnVal == 'AC') {
            clearScreen();

        }
        // If eval key is pressed, send equation to server
        else if(btnVal == '=') {
            // Set equation to the input value
            var equation = inputVal;


            // Replace all instances of x and ÷ with * and / respectively.
            equation = equation.replace(/x/g, '*').replace(/÷/g, '/').replace
            (/−/g, '-');

            // Checks input length and sends equation if condition is met
            // else user gets error
            if(input.innerHTML.length > 2) {
                sendEquation();
            }
            else {
                alert('Error! Please enter valid a expression.');
            }
        }

        // If operator is pressed
        else if(operators.indexOf(btnVal) >= 0) {
            // Get the last character from the equation
            var lastChar = inputVal[inputVal.length - 1];

            // Only add operator if input is not empty and there is no operator
            // at the last entry
            if(inputVal != '' && operators.indexOf(lastChar) == -1) {
                input.innerHTML += btnVal;
            }

            // Allow minus if the string is empty
            else if(inputVal == '' && btnVal == '−') {
                input.innerHTML += btnVal;
            }

            // Replace the last operator (if exists) with the newly pressed
            // operator
            if(operators.indexOf(lastChar) > -1 && inputVal.length > 1) {
                input.innerHTML = inputVal.replace(/.$/, btnVal);
            }

            decimalAdded =false;
        }

        // Prevent more decimals to be added once it's set.
        else if(btnVal == '.') {
            if(!decimalAdded) {
                input.innerHTML += btnVal;
                decimalAdded = true;
                changeClearText();
            }
        }

        // Add any other key pressed.
        else {
            // Check if results div has been generated. Remove it if present.
            if(results === null) {
                input.innerHTML += btnVal;

            }
            else {
                results.remove();
                resetInput();
                input.innerHTML += btnVal;

            }
            changeClearText();
        }

        // Prevent page jumps on key press
        e.preventDefault();
    }
}