import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:better_player/better_player.dart';
import 'package:play_lab/data/controller/movie_details_controller/movie_details_controller.dart';
import 'package:play_lab/core/utils/url_container.dart';
import 'player_pre_loader_image.dart';

class VideoPlayerWidget extends StatefulWidget {
  final MovieDetailsController controller;
  const VideoPlayerWidget({super.key, required this.controller});

  @override
  State<VideoPlayerWidget> createState() => _VideoPlayerWidgetState();
}

class _VideoPlayerWidgetState extends State<VideoPlayerWidget> {
  BetterPlayerController? _betterPlayerController;

  @override
  void initState() {
    super.initState();
    _initializeBetterPlayer();
  }

  void _initializeBetterPlayer() {
    final controller = widget.controller;

    if (controller.lockVideo || controller.videoUrl.isEmpty) return;

    final String url = controller.videoUrl;

    final BetterPlayerDataSource dataSource = BetterPlayerDataSource(
      BetterPlayerDataSourceType.network,
      url,
      headers: {
        'Referer': 'https://voidboost.net',
        'User-Agent': 'Mozilla/5.0 (Android)',
      },
    );

    _betterPlayerController = BetterPlayerController(
      BetterPlayerConfiguration(
        autoPlay: true,
        aspectRatio: 16 / 9,
        fit: BoxFit.contain,
      ),
      betterPlayerDataSource: dataSource,
    );
  }

  @override
  void dispose() {
    _betterPlayerController?.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final controller = widget.controller;

    if (controller.playVideoLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (controller.lockVideo || controller.videoUrl.isEmpty) {
      return PlayerPreLoaderImage(
        image: '${UrlContainer.baseUrl}${controller.playerAssetPath}${controller.playerImage}',
        isShowLoader: controller.videoUrl.isEmpty && !controller.lockVideo,
      );
    }

    return AspectRatio(
      aspectRatio: 16 / 9,
      child: BetterPlayer(controller: _betterPlayerController!),
    );
  }
}
