<?php

namespace Aammui\LaravelMedia\Tests;

use Aammui\LaravelMedia\Enum\Responsive;
use Aammui\LaravelMedia\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class MediaTest extends TestCase
{
    #[Test]
    public function media_can_attached_to_a_model()
    {
        $image = UploadedFile::fake()->image('avatar.jpg');
        $model = TestModel::create();
        $model->addMedia($image);
        $this->assertEquals(1, count(Media::all()));
    }

    #[Test]
    public function media_can_add_to_collection()
    {
        $image = UploadedFile::fake()->image('avatar.jpg');
        $model = TestModel::create();
        $model->toCollection('profile')->addMedia($image);
        $model->toCollection('cover')->addMedia($image);
        $model->addMedia($image);
        $this->assertEquals(1, $model->fromCollection('profile')->getMedia()->count());
        $this->assertEquals(1, $model->fromCollection('cover')->getMedia()->count());
        $this->assertEquals(3, $model->getMedia()->count());
    }

    /**
     * @todo Fix file upload from directory
     */
    #[Test]
    public function media_can_add_from_path()
    {
        //        $path = __DIR__ . '/TestFiles/sample.pdf';
        //        $model = TestModel::create();
        //        $model->toCollection('profile')
        //            ->addMediaFromPath($path);
        //        $this->assertEquals(1, $model->getMedia()->count());

        $this->markTestSkipped();
    }

    #[Test]
    public function media_can_add_from_url()
    {
        $url   = "http://www.africau.edu/images/default/sample.pdf";
        $model = TestModel::create();

        $model->toCollection('document')->addMediaFromUrl($url);
        $this->assertEquals(
            $url,
            $model->fromCollection('document')->getMedia()->first()->link()
        );
    }

    #[Test]
    public function multiple_media_can_linked_from_server()
    {
        $url   = "http://www.africau.edu/images/default/sample.pdf";
        $model = TestModel::create();
        $model->toCollection('document')->addMediaFromUrl($url);
        $this->assertEquals(
            $url,
            $model->fromCollection('document')->getMedia()->first()->link()
        );

        $url = "https://picsum.photos/200/300";
        $model->toCollection('image')->addMediaFromUrl($url);
        $this->assertEquals(2, $model->getMedia()->count());
        $this->assertEquals(
            $url,
            $model->fromCollection('image')->getMedia()->first()->link()
        );
    }

    #[Test]
    public function media_can_store_and_retrieve_from_particular_storage()
    {
        Storage::fake("s3");

        /** @var TestModel $model */
        $model = TestModel::query()->create();

        $image = UploadedFile::fake()->image('avatar.jpg');
        $model->toDisk("local")->addMedia($image);
        $model->toDisk("s3")->addMedia($image);

        $this->assertEquals(1, $model->fromDisk("local")->getMedia()->count());
        $this->assertEquals(1, $model->fromDisk("s3")->getMedia()->count());
        $this->assertEquals(2, $model->getMedia()->count());
    }
}
