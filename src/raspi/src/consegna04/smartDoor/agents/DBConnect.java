package consegna04.smartDoor.agents;

import java.sql.*;

//My class for the connection with Mysql Database
public class DBConnect {

    private Connection con;
    private Statement st;
    private ResultSet rs;

    public DBConnect() {
        try {
            Class.forName("com.mysql.jdbc.Driver");

            this.con = DriverManager.getConnection(databaseAddress, username, password);
            this.st = con.createStatement();
            
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    
    //Method to query the DB and get the user if he exists.
    public User getUser(String username) {
        try{
            this.rs = this.st.executeQuery("SELECT username, password, confirmed, salt FROM user WHERE username='" + username + "'");
            if(rs.next()) {
                return new User(rs.getString("username"), rs.getString("password"), rs.getString("confirmed"), rs.getString("salt"));
            } else return null;
        }catch(Exception e) {
            e.printStackTrace();
        }
        return null;
    }
    
    //Method called to insert a log into the DB
    //LogType : SUCCESS -> user entered into the room
    //          FAILED  -> user didn't enter into the room in time
    public void insertLog(String username, String type) {
        try{
            java.util.Date dt = new java.util.Date();
            java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            String currentTime = sdf.format(dt);
            this.st.executeUpdate("INSERT INTO log VALUES('" + username + "', '" + currentTime + "', '" + type + "')");
        }catch(Exception e) {
            e.printStackTrace();
        }
    }
    
    //Method to update the temperature of the room into DB
    public void updateTemperature(String newTemp) {
        try{
            this.st.executeUpdate("UPDATE temperature SET temp='" + newTemp + "' WHERE idTemperature='1'");
        }catch(Exception e) {
            e.printStackTrace();
        }
    }
    
    //Method to update the intensity of ledValue(Arduino) into DB
    public void updateIntensity(String newInt) {
        try{
            this.st.executeUpdate("UPDATE intensity SET value='" + newInt + "' WHERE idIntensity='1'");
        }catch(Exception e) {
            e.printStackTrace();
        }
    }
}
