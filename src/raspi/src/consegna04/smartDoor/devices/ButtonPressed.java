package consegna04.smartDoor.devices;

import consegna04.smartDoor.event.Event;

public class ButtonPressed implements Event {
	private ButtonImpl source;
	
	public ButtonPressed(ButtonImpl source){
		this.source = source;
	}
	
	public ButtonImpl getSourceButton(){
		return source;
	}
}
