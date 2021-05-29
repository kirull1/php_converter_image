Converter image
===============

This thing converts a picture to text. Then it can be sent somewhere.

Requirement
-----------
The project requires [ffmpeg](https://www.ffmpeg.org/).
You may also need to configure your php (Enable GD module).

Using
-----
### To split the video into parts.
```php
$video = new video('ffmpeg.exe');
$video->get_img('file.mp4', 'img\video\img%d.jpg', 30);
```
When calling the class, it is required to specify the path to the ffmpeg file (If ffmpeg is installed on the system, then it will be enough to write ffmpeg).
When calling the get_img function, you need to specify the path to the video file, the path for saving frames, and fps.

```php
$video->get_video('img/convert/img%d.jpg', 'video.mp4', 30);
```
Also, if you need to collect video from modified images, then you can do it using the get_video function.
You will need to fill in the parameters (path to images, title for video, fps)

### To convert image
```php
$image = new image;
$image::convert("img.jpg", null, 1);   
```
In the function, the cover must be specified (path to the image, saving parameter, saving type).
If you need to convert a picture to text, see the example.

```php
$image::convert("img.jpg", "image.jpg", 0);   
```
If you need to keep it altered.

### You can change the photo.
```php
$image::squeeze("img.jpg", "img/video", "img/convert", 100, 40);
$image::rotate("img/convert/img.jpg", 90, true); 
```
squeeze function (image path, source file path, modified file path, width, height).
rotate function (path to picture, degree of rotation, mirroring).

```php
$image->count;
```
The variable count contains the number of files in the folder.

```php
for ($i=1; $i <= $image->count; $i++) { 
    echo $image::convert("img/convert/img$i.jpg", null, 1);   
    usleep(23000);
}
```
An example of outputting a converted image.