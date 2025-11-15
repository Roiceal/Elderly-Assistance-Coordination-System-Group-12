// Function to check if a cookie exists
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

// Function to show cookie consent banner
function showCookieBanner() {
    const banner = document.getElementById('cookieConsent');
    if (!getCookie('cookies_accepted')) {
        banner.style.display = 'block';
    }
}

// Function to accept cookies
function acceptCookies() {
    document.cookie = "cookies_accepted=true; path=/; max-age=" + 60*60*24*365;
    document.getElementById('cookieConsent').style.display = 'none';
}
function declineCookies() {
    document.cookie = "cookies_accepted=false; path=/; max-age=" + 60*60*24*365;
    document.getElementById('cookieConsent').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    showCookieBanner();
  const acceptBtn = document.getElementById('acceptCookies');
    if (acceptBtn) {
        acceptBtn.addEventListener('click', acceptCookies);
    }

    const declineBtn = document.getElementById('declineCookies');
    if (declineBtn) {
        declineBtn.addEventListener('click', declineCookies);
    }
});

