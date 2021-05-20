import java.io.File;
import java.util.ArrayList;
import com.alibaba.fastjson.JSON;

public class App
{
    public static void main(String[] args)
    {
        ArrayList<String> dirs = new ArrayList<>();
        File[] files = File.listRoots();
        for (File file : files)
        {
            if (!file.isDirectory()) {
                continue ;
            }
            String path = file.getPath();
            dirs.add(path);
        }
        int size = dirs.size();
        String[] res = new String[size];
        // 转换
        dirs.toArray(res);
        String json = JSON.toJSONString(res);
        System.out.print(json);
        System.exit(0);
    }
}
