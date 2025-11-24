let countDownDate = new Date("december 1, 2025 10:00:00").getTime();

let x = setInterval(function() {
    let now = new Date().getTime();
    let distance = countDownDate - now;
    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.querySelector("#time").innerHTML = `
    <div class="tag">
    <span class="value">${days}</span>
    <span class="label">days</span>
    </div>
    <div class="tag">
    <span class="value">${hours}</span>
    <span class="label">hours</span>
    </div>
    <div class="tag">
    <span class="value">${minutes}</span>
    <span class="label">minutes</span>
    </div>
    <div class="tag">
    <span class="value">${seconds}</span>
    <span class="label">seconds</span>
    </div>
    `;
    if (distance < 0) {
        clearInterval(x);
        document.querySelector("#time").innerHTML = "<span>Terminé !</span>";
    }
}, 1000);

