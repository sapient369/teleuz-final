import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:play_lab/constants/my_strings.dart';
import 'package:play_lab/core/helper/shared_pref_helper.dart';
import 'package:play_lab/core/helper/string_format_helper.dart';
import 'package:play_lab/core/route/route.dart';
import 'package:play_lab/core/utils/dimensions.dart';
import 'package:play_lab/core/utils/my_color.dart';
import 'package:play_lab/core/utils/url_container.dart';
import 'package:play_lab/data/controller/home/home_controller.dart';
import 'package:play_lab/data/services/api_service.dart';
import 'package:play_lab/view/components/buttons/category_button.dart';
import 'package:play_lab/view/components/dialog/app_dialog.dart';
import 'package:play_lab/view/components/dialog/login_dialog.dart';
import 'package:play_lab/view/components/show_custom_snackbar.dart';
import '../../../../../components/custom_text_form_field.dart';
import '../../../../all_live_tv/widget/live_tv_grid_item/live_tv_grid_item.dart';
import 'package:play_lab/view/screens/all_live_tv/widget/all_live_tv_shimmer/all_live_tv_shimmer.dart';
import 'package:play_lab/core/utils/styles.dart';

class HomeLiveTvList extends StatelessWidget {
  const HomeLiveTvList({super.key});

  @override
  Widget build(BuildContext context) {
    return GetBuilder<HomeController>(
      builder: (controller) {
        if (controller.liveTvLoading) {
          return const AllLiveTvShimmer();
        }

        final prefs = Get.find<ApiClient>().sharedPreferences;
        final unlocked = prefs.getBool(SharedPreferenceHelper.adultUnlockedKey) ?? false;
        final visibleList = controller.televisionList
            .where((e) => !e.isAdult || unlocked)
            .toList();

        return Column(
          children: [
            if (!unlocked &&
                controller.televisionList.any((e) => e.isAdult))
              Padding(
                padding: const EdgeInsets.all(15),
                child: CategoryButton(
                  text: MyStrings.unlockAdult,
                  press: () {
                    _showPinDialog(controller, context);
                  },
                ),
              ),
            ...List.generate(visibleList.length, (index) {
              final telivision = visibleList[index];
              final channelList = telivision.channels ?? [];
              return Container(
                padding: const EdgeInsets.symmetric(
                    horizontal: Dimensions.space15, vertical: Dimensions.space20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text('${telivision.name} Channels ',
                            style: mulishBold.copyWith()),
                        if (double.tryParse(telivision.price ?? '0') != 0 &&
                            !controller.subcribeChannelList
                                .contains(telivision.id.toString()))
                          CategoryButton(
                            text: controller.isSubcribeLoading ==
                                    telivision.id.toString()
                                ? 'Loading..'
                                : MyStrings.subscribeNow,
                            press: () {
                              if (controller.homeRepo.apiClient.isAuthorizeUser()) {
                                AppDialog().subcribcritionAlert(
                                  context,
                                  () {
                                    controller.subcribeNow(telivision);
                                  },
                                  msgText:
                                      'Are you sure to subscribe to this channel group?\nMonthly subscription price is ${controller.currencySym}${StringConverter.roundDoubleAndRemoveTrailingZero(telivision.price ?? '0')} ${controller.currency}',
                                );
                              } else {
                                CustomSnackbar.showCustomSnackbar(
                                    errorList: [MyStrings.plsLoginAndSubscribeToWatch],
                                    msg: [],
                                    isError: true);
                              }
                            },
                          )
                      ],
                    ),
                    const SizedBox(height: Dimensions.space20),
                    Wrap(
                      crossAxisAlignment: WrapCrossAlignment.start,
                      alignment: WrapAlignment.start,
                      children: List.generate(channelList.length, (i) {
                        return LiveTvGridItem(
                          liveTvName: channelList[i].title ?? '',
                          imageUrl:
                              '${UrlContainer.baseUrl}${controller.televisionImagePath}/${channelList[i].image}',
                          press: () {
                            if (controller.isAuthorized()) {
                              if (double.tryParse(telivision.price ?? '0') == 0 ||
                                  controller.subcribeChannelList
                                      .contains(telivision.id.toString())) {
                                Get.toNamed(RouteHelper.liveTvDetailsScreen,
                                    arguments: channelList[i].id);
                              } else {
                                CustomSnackbar.showCustomSnackbar(
                                    errorList: [MyStrings.plsSubscribeToWatch],
                                    msg: [],
                                    isError: true);
                              }
                            } else {
                              showLoginDialog(context);
                            }
                          },
                        );
                      }),
                    )
                  ],
                ),
              );
            })
          ],
        );
      },
    );
  }

  void _showPinDialog(HomeController controller, BuildContext context) {
    final pinController = TextEditingController();
    Get.defaultDialog(
      title: MyStrings.enterPin,
      content: InputTextFieldWidget(
        controller: pinController,
        hintText: MyStrings.enterPin,
        keyboardType: TextInputType.number,
      ),
      textConfirm: MyStrings.confirm,
      onConfirm: () {
        controller.unlockAdultPin(pinController.text.trim());
        Get.back();
      },
    );
  }
}
