package embedded.smartdoor;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;

import java.util.Set;

//Public utility class with one static method to return the target device from the set of all
//Bonded devices.
public class BluetoothUtils {

    public static BluetoothDevice findPairedDevice(String deviceName, BluetoothAdapter adapter){
        BluetoothDevice targetDevice = null;

        Set<BluetoothDevice> pairedList = adapter.getBondedDevices();

        if (pairedList.size() > 0) {
            for (BluetoothDevice device : pairedList){
                if (device.getName().equals(deviceName)){
                    targetDevice = device;
                }
            }
        }

        return targetDevice;
    }
}
