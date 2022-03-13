import AppStorage from '../class/AppStorage'
import Token from '../class/Token'

class User{
    // login(data){
    //     axios.post('/api/v1/login',data)
    //     .then(response => {
    //         this.responseAfterLogin(response)
    //         this.$router.push({name:'home'})
    //     })
    //     .catch(error =>
    //         console.log(error)
    //     )
    // }

    responseAfterLogin(response){
        const access_token = response.data.access_token
        if(Token.isValid(access_token)){
            AppStorage.store(
                JSON.stringify(response.data.user),
                response.data.access_token
            )
        }
    }

    hasToken(){
        const storedToken = AppStorage.getToken();
        if(storedToken){
            return Token.isValid(storedToken);
        }
        return false
    }

    isLoggedIn(){
        return this.hasToken();
    }

    logout(){
        AppStorage.clear()
        window.location = '/'
    }

    name(){
        if(this.isLoggedIn){
            return JSON.parse(AppStorage.getUser()).name
        }
        return false
    }

    id(){
        if(this.isLoggedIn){
            return JSON.parse(AppStorage.getUser()).id
        }
        return false
    }

}

export default User = new User();
