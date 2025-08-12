import 'package:play_lab/data/model/global/telivision/channel.dart';

class Telivison {
  String? id; //
  String? name;
  String? price;
  String? status; //
  String? createdAt;
  String? updatedAt;
  bool isAdult;
  List<Channel>? channels;

  Telivison({
    this.id,
    this.name,
    this.price,
    this.status,
    this.createdAt,
    this.updatedAt,
    this.channels,
    this.isAdult = false,
  });

  factory Telivison.fromJson(Map<String, dynamic> json) => Telivison(
        id: json["id"].toString(),
        name: json["name"].toString(),
        price: json["price"].toString(),
        status: json["status"].toString(),
        createdAt: json["created_at"].toString(),
        updatedAt: json["updated_at"].toString(),
        isAdult: json["is_adult"] is bool
            ? json["is_adult"] as bool
            : (json["is_adult"] ?? 0).toString() == '1',
        channels: json["channels"] == null
            ? []
            : List<Channel>.from(
                json["channels"]!.map((x) => Channel.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "price": price,
        "status": status,
        "created_at": createdAt,
        "updated_at": updatedAt,
        "is_adult": isAdult ? 1 : 0,
        "channels": channels == null ? [] : List<dynamic>.from(channels!.map((x) => x.toJson())),
      };
}
