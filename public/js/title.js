document.addEventListener('DOMContentLoaded', init);

function init() {
    const nameToggle = document.getElementById('toggle1');
    const nameInput = document.querySelector("input[name='name']");
    nameToggle.addEventListener('click', function() {
        nameInput.disabled = nameToggle.checked;
    });

    const birthToggle = document.getElementById('toggle2');
    const birthInput = document.querySelector("input[name='birth_year']");
    birthToggle.addEventListener('click', function() {
        birthInput.disabled = birthToggle.checked;
    });

    const genderToggle = document.getElementById('toggle3');
    const genderInput = document.querySelector("select[name='gender']");
    genderToggle.addEventListener('click', function() {
        genderInput.disabled = genderToggle.checked;
    });
}
