export default {
    getToken() {
        let token = localStorage.getItem("token");
        return token;
    }
};
