# Protobuf How To

⚠️ Protobuf classes are generated, do NOT modify the classes manually ⚠️

## Setup

- Install the protobuf compiler

```sh
sudo apt install protobuf-compiler
```

- Install the protobuf PHP extension

```sh
sudo pecl install protobuf
```

- Install the composer package

```sh
composer require google/protobuf
```

Reference: [protobuf/php](https://github.com/protocolbuffers/protobuf/tree/main/php)

## Generate the class

- Edit the *.proto file

>Reference: [protocol-buffers/docs/proto3](https://developers.google.com/protocol-buffers/docs/proto3)

- Run the following cli command:

```bash
protoc --proto_path=app/Protobuf/ --php_out=. app/Protobuf/GoogleAuth.proto
```

Protoc will generate the files in `app/Protobuf` while we want them to be in `App/protobuf`.

- Moves the files from `app/Protobuf` to `App/protobuf`
- Update the namespace of the moved files to match their new location

>Reference: [protocol-buffers/docs/php-generated](https://developers.google.com/protocol-buffers/docs/reference/php-generated#invocation)
