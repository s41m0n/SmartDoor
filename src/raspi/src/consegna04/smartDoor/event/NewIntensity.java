package consegna04.smartDoor.event;

//This is an event for a new intensity of the ledValue in my SmartDoor(Arduino)
public class NewIntensity implements Event {

    private String intensity;
    
    public NewIntensity(String value) {
        this.intensity = value;
    }
    
    public String getIntensity() {
        return this.intensity;
    }
}
