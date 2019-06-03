package consegna04.smartDoor.common;

import consegna04.smartDoor.event.Event;

public interface Observer {

	boolean notifyEvent(Event ev);
}
