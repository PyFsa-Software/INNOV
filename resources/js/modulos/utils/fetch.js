export function fetchHelper(url, data = {}, method = "GET") {
    const TOKEN = document.getElementsByName("_token")[0].value;
    return fetch(url, {
        // Return promise
        method: method,
        withCredentials: true,
        // credentials: 'include',
        headers: {
            "X-CSRF-TOKEN": TOKEN,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((res) => res.json())
        .then((result) => {
            console.log(result);
            return result;
        })
        .catch((error) => {
            console.log(error);
        });
}
