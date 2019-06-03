package consegna04.smartDoor.devices;

import consegna04.smartDoor.event.Event;

public class ButtonReleased implements Event {
	private ButtonImpl source;
	
	public ButtonReleased(ButtonImpl source){
		this.source = source;
	}
	
	public ButtonImpl getSourceButton(){
		return source;
	}
}
