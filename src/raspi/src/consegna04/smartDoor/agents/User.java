package consegna04.smartDoor.agents;

//My class for a user.
//He has 4 fields:
//username -> user email
//password -> user password
//confirmed -> set to 1 if he's confirmed, NULL if it is not
//salt -> the private key used to encrypt the password
public class User {

    private String username;
    private String password;
    private String confirmed;
    private String salt;
    
    public User(String username, String password, String confirmed, String salt) {
        this.username = username;
        this.password = password;
        this.confirmed = confirmed;
        this.salt = salt;
    }
    
    public String toString() {
        return "" + this.username + " " + this.password + " " + this.confirmed + " " + this.salt;
    }
    
    public String getUsername() {
        return this.username;
    }
   
    public String getPassword() {
        return this.password;
    }
    
    public String getSalt() {
        return this.salt;
    }
    
    public boolean isConfirmed() {
        return this.confirmed.equals("1");
    }
}
