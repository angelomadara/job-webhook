class AppStorage{
    /**
     * call this func whenever the user login and store the details
     * @param {user payload} user
     * @param {user token} token
     */
    store(user,token){
        this.storeUser(user)
        this.storeToken(token)
    }

    storeToken(token){
        localStorage.setItem('token',token)
    }

    storeUser(user){
        localStorage.setItem('user',user)
    }

    getUser(){
        return localStorage.getItem('user')
    }

    getToken(){
        return localStorage.getItem('token')
    }

    /**
     * call this func whenever the user logouts to clear the localstorage
     */
    clear(){
        localStorage.removeItem('user')
        localStorage.removeItem('token')
    }
}


export default AppStorage = new AppStorage()
