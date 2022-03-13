class Token{
    /**
     * check the token if valid and if the token is from the server
     * @param {user token} token
     * @returns boolean
     */
    isValid(token){
        const payload = this.payload(token);
        // console.log(payload.iss)
        if(payload){
            return payload.iss == `${location.origin}/api/v1/login` ? true : false
        }
        return false;
    }

    /**
     *
     * @param {access_token} token
     * @returns object
     */
    payload(token){
        const payload = token.split('.')[1];
        // console.log(this.decode(payload))
        return this.decode(payload);
    }

    /**
     *
     * @param {access_token} payload
     * @returns decoded base64 token
     */
    decode(payload){
        return JSON.parse(atob(payload))
    }
}

export default Token = new Token()
