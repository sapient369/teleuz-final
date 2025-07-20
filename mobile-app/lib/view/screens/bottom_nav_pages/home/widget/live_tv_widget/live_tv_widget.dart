import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:play_lab/view/components/dialog/login_dialog.dart';
import '../../../../../../core/route/route.dart';
import '../../../../../../core/utils/dimensions.dart';
import '../../../../../../core/utils/url_container.dart';
import '../../../../../../data/controller/home/home_controller.dart';
import '../../../../../../data/services/api_service.dart';
import '../../../../../../core/helper/shared_pref_helper.dart';
import '../../../../all_live_tv/widget/live_tv_grid_item/live_tv_grid_item.dart';
import '../../shimmer/live_tv_shimmer.dart';

class LiveTvWidget extends StatefulWidget {
  const LiveTvWidget({super.key});

  @override
  State<LiveTvWidget> createState() => _LiveTvWidgetState();
}

class _LiveTvWidgetState extends State<LiveTvWidget> {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<HomeController>(
      builder: (controller) => Padding(
        padding: const EdgeInsets.only(left: Dimensions.homePageLeftMargin),
        child: controller.liveTvLoading
            ? SizedBox(height: 120, width: MediaQuery.of(context).size.width, child: const LiveTvShimmer())
            : SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: Row(
                  children: List.generate(
                    controller.televisionList
                        .where((e) => !e.isAdult || (Get.find<ApiClient>().sharedPreferences.getBool(SharedPreferenceHelper.adultUnlockedKey) ?? false))
                        .length,
                    (index) {
                      final visibleList = controller.televisionList
                          .where((e) => !e.isAdult || (Get.find<ApiClient>().sharedPreferences.getBool(SharedPreferenceHelper.adultUnlockedKey) ?? false))
                          .toList();
                      final firstChannel = visibleList[index].channels?.isNotEmpty == true
                          ? visibleList[index].channels!.first
                          : null;
                      return LiveTvGridItem(
                        liveTvName: visibleList[index].name?.tr ?? '',
                        imageUrl: firstChannel != null
                            ? '${UrlContainer.baseUrl}${controller.televisionImagePath}/${firstChannel.image}'
                            : '',
                        press: () {
                          if (controller.isAuthorized() == false) {
                            showLoginDialog(context);
                          } else {
                            Get.toNamed(RouteHelper.allLiveTVScreen);
                          }
                        },
                      );
                    },
                  ),
                ),
              ),
      ),
    );
  }
}
