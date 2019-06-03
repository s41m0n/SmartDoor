package consegna04.smartDoor.common;

public interface Serial {
	
	/* async interface */
	boolean isMsgAvailable();
	void	sendMsg(String msg);
	
	/* sync interface */
	String 	waitForMsg() throws InterruptedException;
}
