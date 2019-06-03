package consegna04.smartDoor.event;

//A login event, to log the user into the system.
public class Login implements Event{
    private String username;
    private String password;
    
    public Login(String username, String password) {
        this.username = username;
        this.password = password;
    }
    
    public String getUsername() {
        return this.username;
    }
    
    public String getPassword() {
        return this.password;
    }
}