package consegna04.smartDoor.event;

//This is an event for a new temperature.
public class NewTemperature implements Event{
    
    private String temperature;
    
    public NewTemperature(String temp) {
        this.temperature = temp;
    }
    
    public String getTemperature() {
        return this.temperature;
    }
}
